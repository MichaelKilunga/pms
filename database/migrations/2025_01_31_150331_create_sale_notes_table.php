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
        Schema::create('sale_notes', function (Blueprint $table) {
            $table->id();
            //name
            $table->string('name');
            $table->string('quantity');
            $table->string('unit_price');
            //status
            $table->enum('status', ['promoted', 'Unpromoted','rejected'])->default('Unpromoted');
            //description
            $table->text('description')->nullable();
            //foreign key for pharmacy id
            $table->foreignId('pharmacy_id')->constrained('pharmacies')->onDelete('cascade');
            //foreign key for staff id
            $table->foreignId('staff_id')->constrained('users')->onDelete('cascade'); // User making the sale
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_notes');
    }
};
