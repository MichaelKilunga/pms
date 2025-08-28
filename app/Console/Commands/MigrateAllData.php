<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MigrateAllData extends Command
{
    protected $signature = 'db:migrate-all-data';
    protected $description = 'Migrate SQLite → MySQL (robust): sanitize nulls, numbers, and date formats per-column type';

    // Columns that should explicitly become null when empty
    protected $forceNullColumns = [
        'users' => [
            'current_team_id',
            'email_verified_at',
            'two_factor_confirmed_at',
            'two_factor_recovery_codes',
            'two_factor_secret',
            'profile_photo_path',
        ],
        // add more table => [cols] as needed
    ];

    public function handle()
    {
        $this->info('Starting data migration from SQLite → MySQL...');

        $mysql = DB::connection('mysql');
        $sqlite = DB::connection('sqlite_old');

        // Disable FK checks (we will handle referential issues by ordering/truncation)
        $mysql->statement('SET FOREIGN_KEY_CHECKS=0;');

        // Get migration-based ordering (best-effort)
        $orderedTables = [];
        try {
            $migrationFiles = $mysql->table('migrations')->orderBy('batch')->orderBy('migration')->pluck('migration');
            foreach ($migrationFiles as $migration) {
                if (preg_match('/create_(.*?)_table/', $migration, $matches)) {
                    $orderedTables[] = $matches[1];
                }
            }
        } catch (\Throwable $e) {
            // ignore if migrations table doesn't exist
        }

        // Get SQLite tables
        $sqliteTablesRaw = $sqlite->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
        $sqliteTables = collect($sqliteTablesRaw)->pluck('name')->toArray();

        $tables = collect($orderedTables)
            ->merge(array_diff($sqliteTables, $orderedTables))
            ->filter(fn($t) => $t !== 'migrations')
            ->values()
            ->toArray();

        foreach ($tables as $table) {
            $this->info("Migrating table: {$table}");

            // Read rows from SQLite
            try {
                $rows = $sqlite->table($table)->get();
            } catch (\Throwable $e) {
                $this->warn("  - Skipping {$table} (not found in SQLite).");
                continue;
            }

            // Ensure target table exists in MySQL
            try {
                $mysql->getSchemaBuilder()->getColumnListing($table);
            } catch (\Throwable $e) {
                $this->warn("  - Skipping {$table} (missing in MySQL).");
                continue;
            }

            // Truncate (or delete) target
            try {
                $mysql->table($table)->truncate();
                $this->line("  - Truncated MySQL table {$table}");
            } catch (\Throwable $e) {
                $mysql->table($table)->delete();
                $this->line("  - Truncate failed; deleted rows in {$table}");
            }

            if ($rows->isEmpty()) {
                $this->line("  - No rows to migrate, skipping.");
                continue;
            }

            // Load column metadata for this table
            $colMetaRows = $mysql->select(
                "SELECT COLUMN_NAME, IS_NULLABLE, COLUMN_DEFAULT, DATA_TYPE, COLUMN_TYPE
                 FROM INFORMATION_SCHEMA.COLUMNS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?",
                [env('DB_DATABASE'), $table]
            );

            $colMeta = [];
            foreach ($colMetaRows as $c) {
                $colMeta[$c->COLUMN_NAME] = [
                    'nullable' => strtoupper($c->IS_NULLABLE) === 'YES',
                    'default' => $c->COLUMN_DEFAULT,
                    'data_type' => strtolower($c->DATA_TYPE),
                    'column_type' => $c->COLUMN_TYPE,
                ];
            }

            // Insert in chunks
            foreach ($rows->chunk(500) as $chunkIndex => $chunk) {
                $batch = [];
                foreach ($chunk as $row) {
                    $row = (array) $row;

                    // Normalize placeholders
                    foreach ($row as $k => $v) {
                        if ($v === '?' || $v === 'NULL') $row[$k] = null;
                    }

                    // Force configured columns to null if missing or empty
                    if (isset($this->forceNullColumns[$table])) {
                        foreach ($this->forceNullColumns[$table] as $col) {
                            if (!array_key_exists($col, $row) || $row[$col] === '') {
                                $row[$col] = null;
                            }
                        }
                    }

                    // Per-column sanitization using metadata
                    foreach ($row as $col => $val) {
                        if (!isset($colMeta[$col])) {
                            // unknown column - will be ignored by insert
                            continue;
                        }
                        $meta = $colMeta[$col];

                        // Normalize empty strings
                        if ($val === '') {
                            if ($meta['nullable']) {
                                $row[$col] = null;
                                continue;
                            } elseif ($meta['default'] !== null) {
                                $row[$col] = $meta['default'];
                                continue;
                            }
                        }

                        // DATE / DATETIME / TIMESTAMP handling
                        if (in_array($meta['data_type'], ['date', 'datetime', 'timestamp'])) {
                            // If empty or null
                            if (empty($val)) {
                                if ($meta['nullable']) {
                                    $row[$col] = null;
                                } else {
                                    // prefer created_at if valid, else now()
                                    if (isset($row['created_at']) && $this->isValidDate($row['created_at'])) {
                                        $row[$col] = $this->formatDateForMySQL($row['created_at'], $meta['data_type']);
                                    } else {
                                        $row[$col] = $this->formatDateForMySQL(now(), $meta['data_type']);
                                    }
                                }
                                continue;
                            }

                            // Attempt to parse & format for the specific column type
                            $formatted = $this->tryFormatDateForType($val, $meta['data_type']);
                            if ($formatted === null) {
                                // fallback
                                if ($meta['nullable']) {
                                    $row[$col] = null;
                                } else {
                                    if (isset($row['created_at']) && $this->isValidDate($row['created_at'])) {
                                        $row[$col] = $this->formatDateForMySQL($row['created_at'], $meta['data_type']);
                                    } else {
                                        $row[$col] = $this->formatDateForMySQL(now(), $meta['data_type']);
                                    }
                                }
                            } else {
                                $row[$col] = $formatted;
                            }
                            continue;
                        }

                        // Numeric integer family
                        if (in_array($meta['data_type'], ['int', 'integer', 'bigint', 'smallint', 'mediumint', 'tinyint'])) {
                            if ($val === '' || $val === null) {
                                $row[$col] = $meta['nullable'] ? null : (($meta['default'] !== null) ? $meta['default'] : 0);
                            } else {
                                $row[$col] = is_numeric($val) ? (int)$val : (int)filter_var($val, FILTER_SANITIZE_NUMBER_INT);
                            }
                            continue;
                        }

                        // Decimal/float
                        if (in_array($meta['data_type'], ['decimal', 'double', 'float'])) {
                            if ($val === '' || $val === null) {
                                $row[$col] = $meta['nullable'] ? null : (($meta['default'] !== null) ? $meta['default'] : 0);
                            } else {
                                // sanitize numeric string
                                $row[$col] = is_numeric($val) ? $val : (float) filter_var($val, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            }
                            continue;
                        }

                        // JSON
                        if ($meta['data_type'] === 'json') {
                            if ($val === '' || $val === null) {
                                $row[$col] = $meta['default'] !== null ? $meta['default'] : null;
                            } else {
                                if (is_string($val) && $this->isJson($val)) {
                                    $row[$col] = $val;
                                } else {
                                    $row[$col] = json_encode($val);
                                }
                            }
                            continue;
                        }

                        // ENUM -> accept or fallback to default
                        if (Str::startsWith($meta['column_type'], 'enum(')) {
                            if ($val === '' || $val === null) {
                                $row[$col] = $meta['default'] !== null ? $meta['default'] : null;
                            } else {
                                $row[$col] = $val;
                            }
                            continue;
                        }

                        // Strings: if not nullable and empty, use default or empty string
                        if (in_array($meta['data_type'], ['varchar', 'text', 'char', 'mediumtext', 'longtext'])) {
                            if (($val === '' || $val === null) && !$meta['nullable']) {
                                $row[$col] = $meta['default'] !== null ? $meta['default'] : '';
                            }
                        }
                    } // end each column

                    $batch[] = $row;
                } // end each row in chunk

                if (!empty($batch)) {
                    try {
                        $mysql->table($table)->insert($batch);
                    } catch (\Throwable $e) {
                        $this->error("Insert failed on table {$table}: " . $e->getMessage());
                        // Provide a hint & continue with next chunk/table
                        // optional: write $batch to a debug file here for inspection
                        throw $e;
                    }
                }
            } // end chunk loop

            $this->line("  - Migrated " . count($rows) . " rows.");
        } // end tables loop

        // Re-enable FK checks
        $mysql->statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('✅ Data migration completed!');
    }

    /**
     * Try to parse various date formats and format for the target MySQL type.
     * Returns formatted string or null on failure.
     *
     * @param mixed $value
     * @param string $targetType 'date'|'datetime'|'timestamp'
     * @return string|null
     */
    private function tryFormatDateForType($value, string $targetType): ?string
    {
        // If it's already a DateTime or Carbon, format directly
        if ($value instanceof \DateTime) {
            return $this->formatDateForMySQL($value, $targetType);
        }

        // Clean value: trim, replace T, Z, and multiple spaces; remove stray characters
        $val = trim((string)$value);
        $val = str_replace('T', ' ', $val);
        $val = preg_replace('/Z$/', '', $val);
        $val = preg_replace('/\s+/', ' ', $val);

        // If looks like YYYYMMDD or YYYYMMDDHHMMSS, try to inject separators
        if (preg_match('/^\d{8}$/', $val)) { // YYYYMMDD
            $val = substr($val, 0, 4) . '-' . substr($val, 4, 2) . '-' . substr($val, 6, 2);
        } elseif (preg_match('/^\d{14}$/', $val)) { // YYYYMMDDHHMMSS
            $val = substr($val, 0, 4) . '-' . substr($val, 4, 2) . '-' . substr($val, 6, 2) . ' '
                . substr($val, 8, 2) . ':' . substr($val, 10, 2) . ':' . substr($val, 12, 2);
        }

        // If the target is DATE and the string contains time, strip time part
        if ($targetType === 'date') {
            // extract first YYYY-MM-DD-like pattern
            if (preg_match('/(\d{4}-\d{2}-\d{2})/', $val, $m)) {
                $val = $m[1];
            }
        }

        // Try DateTime parsing
        try {
            $dt = new \DateTime($val);
            $year = (int)$dt->format('Y');
            if ($year < 1000 || $year > 9999) {
                return null;
            }
            return $this->formatDateForMySQL($dt, $targetType);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Format a DateTime (or string) into MySQL target type.
     *
     * @param \DateTime|string $value
     * @param string $targetType
     * @return string
     */
    private function formatDateForMySQL($value, string $targetType): string
    {
        $dt = $value instanceof \DateTime ? $value : new \DateTime($value);
        if ($targetType === 'date') {
            return $dt->format('Y-m-d');
        }
        // datetime or timestamp
        return $dt->format('Y-m-d H:i:s');
    }

    private function isValidDate($value): bool
    {
        return (new \DateTime('@0')) ? @strtotime((string)$value) !== false : false;
    }

    private function isJson($string): bool
    {
        if (!is_string($string)) return false;
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
