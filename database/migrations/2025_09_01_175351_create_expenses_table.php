<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            // Core refs
            $table->date('expense_date')->index();
            $table->foreignId('pharmacy_id')->constrained('pharmacies')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('category_id')->constrained('expense_categories')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();

            // Money
            $table->decimal('amount', 18, 2);
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->decimal('total_amount', 18, 2)->nullable();; // amount + tax - discounts
            $table->string('currency', 3)->default('TZS');

            // Payment
            $table->enum('payment_method', ['cash', 'mobile_money', 'bank_transfer', 'cheque', 'other'])->default('cash')->index();
            $table->string('payment_reference')->nullable(); // txn id, cheque no, etc.
            $table->string('mobile_wallet')->nullable(); // Airtel, M-Pesa, TigoPesa (optional)
            $table->string('bank_account')->nullable();

            // Workflow
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->index();
            $table->foreignId('created_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            // Meta
            $table->string('reference_no')->nullable()->unique(); // internal doc/ref no
            $table->text('description')->nullable();
            $table->json('meta')->nullable(); // extra fields if needed
            
            $table->timestamps();

            // Useful compound indexes
            $table->index(['pharmacy_id', 'expense_date']);
            $table->index(['pharmacy_id', 'category_id', 'expense_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
