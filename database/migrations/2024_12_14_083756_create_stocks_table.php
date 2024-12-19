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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->timestamp('in_date');
            $table->integer('quantity');
            $table->integer('remain_Quantity');
            $table->integer('low_stock_percentage');
            $table->timestamp('expire_date');
            $table->decimal('buying_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->foreignId('staff_id')->constrained('users')->onDelete('cascade'); // User making the stock
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade'); // User making the stock
            $table->foreignId('pharmacy_id')->constrained('pharmacies')->onDelete('cascade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
