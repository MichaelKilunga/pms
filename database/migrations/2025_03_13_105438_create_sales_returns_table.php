<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sales_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade'); // Reference to the original sale
            $table->foreignId('pharmacy_id')->constrained('pharmacies')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->foreignId('staff_id')->constrained('users')->onDelete('cascade'); // User processing the return
            $table->integer('quantity');
            $table->decimal('refund_amount', 8, 2)->nullable(); // Refund amount if applicable
            $table->string('reason')->nullable(); // Reason for return
            $table->enum('return_status', ['pending', 'approved', 'rejected'])->default('pending'); // Status of return
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // User approving the return
            $table->timestamp('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_returns');
    }
};
