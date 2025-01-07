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
        Schema::table('packages', function (Blueprint $table) {
            // Remove the "features" column
            $table->dropColumn('features');

            // Add new columns
            $table->integer('number_of_pharmacies')->nullable();
            $table->integer('number_of_pharmacists')->nullable();
            $table->integer('number_of_medicines')->nullable();
            $table->boolean('in_app_notification')->default(false);
            $table->boolean('email_notification')->default(false);
            $table->boolean('sms_notifications')->default(false);
            $table->boolean('online_support')->default(false);
            $table->integer('number_of_owner_accounts')->nullable();
            $table->integer('number_of_admin_accounts')->nullable();
            $table->boolean('reports')->default(false);
            $table->boolean('stock_transfer')->default(false);
            $table->boolean('stock_management')->default(false);
            $table->boolean('staff_management')->default(false);
            $table->boolean('receipts')->default(false);
            $table->boolean('analytics')->default(false);
            $table->boolean('whatsapp_chats')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            // Re-add the "features" column
            $table->text('features')->nullable();

            // Remove the added columns
            $table->dropColumn('number_of_pharmacies');
            $table->dropColumn('number_of_pharmacists');
            $table->dropColumn('number_of_medicines');
            $table->dropColumn('in_app_notification');
            $table->dropColumn('email_notification');
            $table->dropColumn('sms_notifications');
            $table->dropColumn('online_support');
            $table->dropColumn('number_of_owner_accounts');
            $table->dropColumn('number_of_admin_accounts');
            $table->dropColumn('reports');
            $table->dropColumn('stock_transfer');
            $table->dropColumn('stock_management');
            $table->dropColumn('staff_management');
            $table->dropColumn('receipts');
            $table->dropColumn('analytics');
            $table->dropColumn('whatsapp_chats');
        });
    }
};
