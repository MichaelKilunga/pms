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
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('pricing_strategy')->default('standard')->after('package_id');
            $table->json('details')->nullable()->after('amount');
        });

        Schema::table('pharmacies', function (Blueprint $table) {
            $table->json('config')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn(['pricing_strategy', 'details']);
        });

        Schema::table('pharmacies', function (Blueprint $table) {
            $table->dropColumn(['config']);
        });
    }
};
