<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('system_settings', 'pharmacy_id')) {
            Schema::table('system_settings', function (Blueprint $table) {
                // We might need to drop unique key on 'key' if it exists globally
                // But let's check if we can just add the column first.
                // The previous migration tried to drop unique(['key']).
                // Let's attempt to drop it if it exists, catching exception or check logic?
                // For simplicity, let's just add the column. 
                // If the unique index exists, adding a duplicate key for another pharmacy might fail later, but adding col is fine.
                
                $table->foreignId('pharmacy_id')->nullable()->after('value')->constrained()->onDelete('cascade');
                
                // Re-add composite unique constraint if needed
                 $table->unique(['key', 'pharmacy_id']);
            });
            
             // Separate schema call to drop the old unique index on 'key' if it persists
            try {
                 Schema::table('system_settings', function (Blueprint $table) {
                     $table->dropUnique(['key']);
                 });
            } catch (\Exception $e) {
                // Ignore if index doesn't exist
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_settings', function (Blueprint $table) {
            if (Schema::hasColumn('system_settings', 'pharmacy_id')) {
                 $table->dropForeign(['pharmacy_id']);
                 $table->dropColumn('pharmacy_id');
            }
        });
    }
};
