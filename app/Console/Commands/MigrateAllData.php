<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateAllData extends Command
{
    protected $signature = 'db:migrate-all-data';
    protected $description = 'Migrate all tables and data from SQLite to MySQL in parent-before-child order, truncating first, without Doctrine.';

    public function handle()
    {
        $this->info('Starting data migration from SQLite → MySQL...');

        // Disable foreign key checks during migration
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');

        // Step 1: Get all SQLite tables
        $sqliteTables = DB::connection('sqlite_old')
            ->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
        $sqliteTables = collect($sqliteTables)->pluck('name')->toArray();

        // Step 2: Define foreign key relationships manually
        // Format: 'child_table' => ['parent_table1', 'parent_table2']
        $foreignKeys = [
            'staff' => ['pharmacies', 'users'],
            'stocks' => ['items', 'pharmacies'],
            'sales' => ['items', 'pharmacies', 'users'],
            'sales_returns' => ['sales', 'pharmacies', 'users'],
            'messages' => ['users', 'conversations'],
            'conversations_users' => ['conversations', 'users'],
            // Add other child->parent relationships as needed
        ];

        // Step 3: Topological sort with cyclic detection
        $tables = $this->sortTablesByForeignKeys($sqliteTables, $foreignKeys);

        // Step 4: Truncate & migrate each table
        foreach ($tables as $table) {
            $this->info("Migrating table: {$table}");

            try {
                $rows = DB::connection('sqlite_old')->table($table)->get();
            } catch (\Exception $e) {
                $this->warn("  - Skipping {$table} (not found in SQLite).");
                continue;
            }

            // Truncate target table
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

            // Insert in chunks
            foreach ($rows->chunk(500) as $chunk) {
                $data = $chunk->map(fn($row) => (array) $row)->toArray();
                DB::connection('mysql')->table($table)->insert($data);
            }

            $this->line("  - Migrated " . count($rows) . " rows.");
        }

        // Re-enable foreign key checks
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('✅ Data migration completed successfully!');
    }

    /**
     * Topological sort of tables based on foreign keys, appends cyclic tables at the end
     */
    private function sortTablesByForeignKeys(array $tables, array $foreignKeys): array
    {
        $sorted = [];
        $visited = [];
        $cyclicTables = [];

        $visit = function ($table) use (&$visit, &$sorted, &$visited, &$cyclicTables, $foreignKeys) {
            if (isset($visited[$table])) {
                if ($visited[$table] === true) return; // already processed
                $cyclicTables[$table] = true; // mark as cyclic
                return;
            }

            $visited[$table] = false; // visiting

            if (isset($foreignKeys[$table])) {
                foreach ($foreignKeys[$table] as $parent) {
                    $visit($parent);
                }
            }

            $visited[$table] = true;
            if (!in_array($table, $sorted)) {
                $sorted[] = $table;
            }
        };

        foreach ($tables as $table) {
            $visit($table);
        }

        // Append cyclic tables at the end
        if (!empty($cyclicTables)) {
            $sorted = array_merge($sorted, array_keys($cyclicTables));
        }

        return $sorted;
    }
}
