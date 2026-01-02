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
        Schema::table('system_settings', function (Blueprint $table) {
            $table->dropUnique(['key']);
            $table->foreignId('pharmacy_id')->nullable()->after('value')->constrained()->onDelete('cascade');
            $table->unique(['key', 'pharmacy_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_settings', function (Blueprint $table) {
            $table->dropForeign(['pharmacy_id']);
            $table->dropUnique(['key', 'pharmacy_id']);
            $table->dropColumn('pharmacy_id');
            $table->unique('key');
        });
    }
};
