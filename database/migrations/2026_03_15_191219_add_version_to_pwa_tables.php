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
        Schema::table('items', function (Blueprint $table) {
            $table->unsignedInteger('version')->default(1)->after('id');
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->unsignedInteger('version')->default(1)->after('id');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->unsignedInteger('version')->default(1)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('version');
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn('version');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('version');
        });
    }
};
