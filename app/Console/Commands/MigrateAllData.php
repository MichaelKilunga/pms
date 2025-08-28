<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MigrateAllData extends Command
{
    protected $signature = 'db:migrate-all-data';
    protected $description = 'Migrate all tables and data from SQLite → MySQL, sanitizing dates, nulls, and not-null columns (remote-safe)';

    // Optional: columns that should explicitly be null if empty (additional safety)
    protected $forceNullColumns = [
        'users' => [
            'current_team_id',
            'email_verified_at',
            'two_factor_confirmed_at',
            'two_factor_recovery_codes',
            'two_factor_secret',
            'profile_photo_path',
        ],
        // Add more if needed
    ];

    public function handle()
    {
        $this->info('Starting data migration from SQLite → MySQL...');

        $mysql = DB::connection('mysql');
        $sqlite = DB::connection('sqlite_old');

        // Disable foreign key checks
        $mysql->statement('SET FOREIGN_KEY_CHECKS=0;');

        // Determine migration order from MySQL migrations table (best-effort)
        $orderedTables = [];
        try {
            $migrationFiles = $mysql->table('migrations')->orderBy('batch')->orderBy('migration')->pluck('migration');
            foreach ($migrationFiles as $migration) {
                if (preg_match('/create_(.*?)_table/', $migration, $matches)) {
                    $orderedTables[] = $matches[1];
                }
            }
        } catch (\Throwable $e) {
            // migrations table might not exist on remote yet — ignore
            $orderedTables = [];
        }

        // Get all SQLite tables
        $sqliteTablesRaw = $sqlite->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
        $sqliteTables = collect($sqliteTablesRaw)->pluck('name')->toArray();

        // Merge migrations order + leftover tables; skip sqlite internal tables
        $tables = collect($orderedTables)
            ->merge(array_diff($sqliteTables, $orderedTables))
            ->filter(fn($t) => $t !== 'migrations')
            ->values()
            ->toArray();

        // Main loop: migrate each table
        foreach ($tables as $table) {
            $this->info("Migrating table: {$table}");

            // Fetch rows from SQLite (skip if table not present)
            try {
                $rows = $sqlite->table($table)->get();
            } catch (\Throwable $e) {
                $this->warn("  - Skipping {$table} (not present in SQLite).");
                continue;
            }

            // If target table does not exist in MySQL, skip
            try {
                $mysql->getSchemaBuilder()->getColumnListing($table);
            } catch (\Throwable $e) {
                $this->warn("  - Skipping {$table} (missing in MySQL).");
                continue;
            }

            // Truncate target table (safe fallback to delete())
            try {
                $mysql->table($table)->truncate();
                $this->line("  - Truncated MySQL table {$table}");
            } catch (\Throwable $e) {
                $mysql->table($table)->delete();
                $this->line("  - Truncate failed, deleted all rows in {$table} instead");
            }

            if ($rows->isEmpty()) {
                $this->line("  - No rows found, skipping.");
                continue;
            }

            // load column metadata from INFORMATION_SCHEMA for the table
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
                    'column_type' => $c->COLUMN_TYPE, // useful for enums
                ];
            }

            // Insert in chunks, sanitizing each row to fit MySQL constraints
            foreach ($rows->chunk(500) as $chunk) {
                $batch = $chunk->map(function ($row) use ($table, $colMeta) {
                    $row = (array) $row;

                    // Convert common placeholder values to null
                    foreach ($row as $k => $v) {
                        if ($v === '?') {
                            $row[$k] = null;
                        }
                        // sqlite sometimes has literals 'NULL' or empty arrays - normalize
                        if ($v === 'NULL') {
                            $row[$k] = null;
                        }
                    }

                    // Force certain columns null if configured
                    if (isset($this->forceNullColumns[$table])) {
                        foreach ($this->forceNullColumns[$table] as $col) {
                            if (!array_key_exists($col, $row) || $row[$col] === '') {
                                $row[$col] = null;
                            }
                        }
                    }

                    // Sanitize dates/datetimes and validate values
                    foreach ($row as $col => $val) {
                        // If MySQL doesn't know this column, we'll keep it — insert will ignore extra keys
                        if (!isset($colMeta[$col])) {
                            continue;
                        }

                        $meta = $colMeta[$col];

                        // Normalize empty string to null for nullable columns
                        if ($val === '' || $val === null) {
                            if ($meta['nullable']) {
                                $row[$col] = null;
                                continue;
                            }
                        }

                        // DATE/TIME types
                        if (in_array($meta['data_type'], ['datetime', 'timestamp', 'date', 'time'])) {
                            if (empty($val)) {
                                // If column is nullable, set null; else fallback to created_at or now()
                                if ($meta['nullable']) {
                                    $row[$col] = null;
                                } else {
                                    // prefer created_at if exists and valid
                                    if (isset($row['created_at']) && $this->isValidDate($row['created_at'])) {
                                        $row[$col] = $this->formatDateForMySQL($row['created_at']);
                                    } else {
                                        $row[$col] = now()->format('Y-m-d H:i:s');
                                    }
                                }
                            } else {
                                // validate date and reformat if valid, else fallback
                                if ($this->isValidDate($val)) {
                                    $row[$col] = $this->formatDateForMySQL($val);
                                } else {
                                    if ($meta['nullable']) {
                                        $row[$col] = null;
                                    } else {
                                        if (isset($row['created_at']) && $this->isValidDate($row['created_at'])) {
                                            $row[$col] = $this->formatDateForMySQL($row['created_at']);
                                        } else {
                                            $row[$col] = now()->format('Y-m-d H:i:s');
                                        }
                                    }
                                }
                            }
                            continue;
                        }

                        // Numeric types
                        if (in_array($meta['data_type'], ['int', 'bigint', 'smallint', 'mediumint', 'tinyint', 'integer'])) {
                            if ($val === '' || $val === null) {
                                if ($meta['nullable']) {
                                    $row[$col] = null;
                                } else {
                                    // try to use column default, otherwise 0
                                    $row[$col] = $meta['default'] !== null ? $meta['default'] : 0;
                                }
                            } else {
                                // ensure integer-ish
                                if (!is_numeric($val)) {
                                    // try casting, else default/fallback
                                    $row[$col] = (int) filter_var($val, FILTER_SANITIZE_NUMBER_INT);
                                } else {
                                    $row[$col] = (int) $val;
                                }
                            }
                            continue;
                        }

                        // Decimal/float
                        if (in_array($meta['data_type'], ['decimal', 'double', 'float'])) {
                            if ($val === '' || $val === null) {
                                if ($meta['nullable']) {
                                    $row[$col] = null;
                                } else {
                                    $row[$col] = $meta['default'] !== null ? $meta['default'] : 0;
                                }
                            } else {
                                // sanitize numeric format
                                $row[$col] = is_numeric($val) ? $val : (float) filter_var($val, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            }
                            continue;
                        }

                        // ENUM -> use default if value invalid
                        if (Str::startsWith($meta['column_type'], 'enum(')) {
                            if ($val === '' || $val === null) {
                                $row[$col] = $meta['default'] !== null ? $meta['default'] : null;
                            } else {
                                $row[$col] = $val;
                            }
                            continue;
                        }

                        // JSON column
                        if ($meta['data_type'] === 'json') {
                            if ($val === '' || $val === null) {
                                $row[$col] = $meta['default'] !== null ? $meta['default'] : null;
                            } else {
                                // ensure valid JSON
                                if (is_string($val) && $this->isJson($val)) {
                                    $row[$col] = $val;
                                } else {
                                    // try serialize to JSON
                                    $row[$col] = json_encode($val);
                                }
                            }
                            continue;
                        }

                        // For other text/varchar types: if not nullable and empty, set '' or default
                        if (in_array($meta['data_type'], ['varchar', 'text', 'char', 'mediumtext', 'longtext'])) {
                            if (($val === '' || $val === null) && !$meta['nullable']) {
                                $row[$col] = $meta['default'] !== null ? $meta['default'] : '';
                            }
                            // otherwise keep value
                            continue;
                        }

                        // Fallback: for anything else, if value is '' and column not nullable, set default or ''
                        if (($val === '' || $val === null) && !$meta['nullable']) {
                            $row[$col] = $meta['default'] !== null ? $meta['default'] : '';
                        }
                    }

                    return $row;
                })->toArray();

                // remove empty rows (defensive)
                $batch = array_filter($batch, fn($r) => !empty($r));

                if (!empty($batch)) {
                    try {
                        $mysql->table($table)->insert($batch);
                    } catch (\Throwable $e) {
                        // log and rethrow with context to help debugging
                        $this->error("Insert failed on table {$table}: " . $e->getMessage());
                        throw $e;
                    }
                }
            } // end chunk loop

            $this->line("  - Migrated " . count($rows) . " rows.");
        } // end tables

        // Re-enable FK checks
        $mysql->statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('✅ Data migration completed successfully!');
    }

    /**
     * Validate if a string/value is a valid date for DateTime constructor.
     */
    private function isValidDate($value): bool
    {
        if (empty($value) || $value === '0000-00-00' || $value === '0000-00-00 00:00:00') {
            return false;
        }
        try {
            new \DateTime($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Format date for MySQL DATETIME (Y-m-d H:i:s).
     */
    private function formatDateForMySQL($value): string
    {
        $dt = new \DateTime($value);
        return $dt->format('Y-m-d H:i:s');
    }

    /**
     * Quick JSON check
     */
    private function isJson($string): bool
    {
        if (!is_string($string)) return false;
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
