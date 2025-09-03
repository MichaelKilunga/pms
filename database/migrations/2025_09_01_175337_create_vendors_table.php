<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('tin')->nullable(); // tax id if applicable
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Tanzania');
            $table->boolean('is_active')->default(true);
            $table->foreignId('pharmacy_id')->constrained('pharmacies')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
            $table->index(['name']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('vendors');
    }
};
