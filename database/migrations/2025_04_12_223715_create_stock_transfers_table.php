<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTransfersTable extends Migration
{
    public function up()
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained('stocks')->onDelete('cascade');
            $table->foreignId('from_pharmacy_id')->constrained('pharmacies')->onDelete('cascade');
            $table->foreignId('to_pharmacy_id')->nullable()->constrained('pharmacies')->onDelete('cascade');
            $table->string('to_pharmacy_name')->nullable();
            $table->string('to_pharmacy_tin')->nullable();
            $table->string('notes')->nullable();
            $table->integer('quantity');
            $table->timestamp('transfer_date')->useCurrent();
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->foreignId('transferred_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_transfers');
    }
}
