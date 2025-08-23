<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateAllData extends Command
{
    protected $signature = 'db:migrate-all-data';
    protected $description = 'Migrate all tables and data from old SQLite DB to current MySQL DB in parent-before-child order, truncating first';

    public function handle()
    {
        $this->info('Starting data migration from SQLite → MySQL...');

        // Disable foreign key checks during migration
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');

        // Step 1: Get migration order from MySQL migrations table
        $migrationFiles = DB::connection('mysql')
            ->table('migrations')
            ->orderBy('batch')
            ->orderBy('migration')
            ->pluck('migration');

        $orderedTables = [];

        foreach ($migrationFiles as $migration) {
            if (preg_match('/create_(.*?)_table/', $migration, $matches)) {
                $orderedTables[] = $matches[1]; // extract table name
            }
        }

        // Step 2: Get all SQLite tables
        $sqliteTables = DB::connection('sqlite_old')
            ->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");

        $sqliteTables = collect($sqliteTables)->pluck('name')->toArray();

        // Step 3: Merge order from migrations + any leftover tables
        $tables = collect($orderedTables)
            ->merge(array_diff($sqliteTables, $orderedTables))
            ->filter(fn($table) => $table !== 'migrations') // skip migrations table
            ->values();

        // Step 4: Truncate & migrate each table in order
        foreach ($tables as $table) {
            $this->info("Migrating table: {$table}");

            try {
                $rows = DB::connection('sqlite_old')->table($table)->get();
            } catch (\Exception $e) {
                $this->warn("  - Skipping {$table} (not found in SQLite).");
                continue;
            }

            // Truncate target table before inserting
            try {
                DB::connection('mysql')->table($table)->truncate();
                $this->line("  - Truncated MySQL table {$table}");
            } catch (\Exception $e) {
                $this->warn("  - Could not truncate {$table} (maybe doesn’t exist in MySQL). Skipping insert.");
                continue;
            }

            if ($rows->isEmpty()) {
                $this->line("  - No rows found, skipping.");
                continue;
            }

            foreach ($rows->chunk(500) as $chunk) {
                $data = $chunk->map(fn($row) => (array) $row)->toArray();
                DB::connection('mysql')->table($table)->insert($data);
            }

            $this->line("  - Migrated " . count($rows) . " rows.");
        }

        // Re-enable foreign key checks
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('✅ Data migration completed successfully (with truncate + parent-before-child order)!');
    }
}
