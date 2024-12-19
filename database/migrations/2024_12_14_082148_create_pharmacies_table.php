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
    Schema::create('pharmacies', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('location')->nullable();
        $table->foreignId('owner_id')->constrained('users')->onDelete('cascade'); // User who owns the pharmacy
        $table->foreignId('admin_id')->constrained('users')->onDelete('null'); // User who owns the pharmacy
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacies');
    }
};
