<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateAllData extends Command
{
    protected $signature = 'db:migrate-all-data';
    protected $description = 'Migrate all tables and data from SQLite → MySQL, handling nullable and date values';

    // Columns that should be null if empty
    protected $nullableColumns = [
        'users' => [
            'current_team_id',
            'email_verified_at',
            'two_factor_confirmed_at',
            'two_factor_recovery_codes',
            'two_factor_secret',
            'profile_photo_path',
        ],
        // Add more tables/columns as needed
    ];

    public function handle()
    {
        $this->info('Starting data migration from SQLite → MySQL...');

        // Disable foreign key checks
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');

        // Step 1: Determine migration order from MySQL migrations table
        $migrationFiles = DB::connection('mysql')
            ->table('migrations')
            ->orderBy('batch')
            ->orderBy('migration')
            ->pluck('migration');

        $orderedTables = [];
        foreach ($migrationFiles as $migration) {
            if (preg_match('/create_(.*?)_table/', $migration, $matches)) {
                $orderedTables[] = $matches[1];
            }
        }

        // Step 2: Get all SQLite tables
        $sqliteTables = DB::connection('sqlite_old')
            ->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
        $sqliteTables = collect($sqliteTables)->pluck('name')->toArray();

        // Step 3: Merge order + leftover tables
        $tables = collect($orderedTables)
            ->merge(array_diff($sqliteTables, $orderedTables))
            ->filter(fn($table) => $table !== 'migrations')
            ->values();

        // Step 4: Migrate each table
        foreach ($tables as $table) {
            $this->info("Migrating table: {$table}");

            try {
                $rows = DB::connection('sqlite_old')->table($table)->get();
            } catch (\Exception $e) {
                $this->warn("  - Skipping {$table} (not found in SQLite).");
                continue;
            }

            try {
                DB::connection('mysql')->table($table)->truncate();
                $this->line("  - Truncated MySQL table {$table}");
            } catch (\Exception $e) {
                $this->warn("  - Could not truncate {$table}. Skipping insert.");
                continue;
            }

            if ($rows->isEmpty()) {
                $this->line("  - No rows found, skipping.");
                continue;
            }

            foreach ($rows->chunk(500) as $chunk) {
                $data = $chunk->map(function ($row) use ($table) {
                    $row = (array) $row;

                    // 1. Nullable columns → null if empty
                    if (isset($this->nullableColumns[$table])) {
                        foreach ($this->nullableColumns[$table] as $col) {
                            if (!array_key_exists($col, $row) || $row[$col] === '') {
                                $row[$col] = null;
                            }
                        }
                    }

                    // 2. Sanitize date/datetime columns
                    foreach ($row as $col => $value) {
                        if ($value !== null && preg_match('/_date|_at$/', $col)) {
                            try {
                                $date = new \DateTime($value);
                                $year = (int)$date->format('Y');
                                if ($year < 1000 || $year > 9999) {
                                    $row[$col] = null;
                                } else {
                                    $row[$col] = $date->format('Y-m-d H:i:s');
                                }
                            } catch (\Exception $e) {
                                $row[$col] = null;
                            }
                        }
                    }

                    return $row;
                })->toArray();

                if (!empty($data)) {
                    DB::connection('mysql')->table($table)->insert($data);
                }
            }

            $this->line("  - Migrated " . count($rows) . " rows.");
        }

        // Re-enable foreign key checks
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('✅ Data migration completed successfully!');
    }
}
