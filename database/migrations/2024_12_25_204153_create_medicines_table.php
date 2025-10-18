<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            //I will use type text for all columns to avoid any issues with the data. because text datatype can store any length of data.
            $table->text('brand_name');
            $table->text('generic_name')->nullable();
            $table->text('category')->nullable();
            $table->text('class')->nullable();
            $table->text('dosage_form')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('approved');
            $table->text('strength')->nullable();
            $table->text('manufacturer')->nullable();
            $table->text('manufacturing_country')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
