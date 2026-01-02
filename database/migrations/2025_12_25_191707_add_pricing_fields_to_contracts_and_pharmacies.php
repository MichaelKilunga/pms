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
            $table->decimal('amount', 12, 2)->nullable()->after('package_id');
            $table->decimal('agent_markup', 12, 2)->default(0)->after('amount');
        });

        Schema::table('pharmacies', function (Blueprint $table) {
            $table->decimal('agent_extra_charge', 12, 2)->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn(['amount', 'agent_markup']);
        });

        Schema::table('pharmacies', function (Blueprint $table) {
            $table->dropColumn('agent_extra_charge');
        });
    }
};
