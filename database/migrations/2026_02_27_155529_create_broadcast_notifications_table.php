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
        Schema::create('broadcast_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->json('channels'); // ['database', 'mail', 'sms', 'whatsapp']
            $table->json('target_criteria')->nullable(); // {'role': 'owner', 'package_id': 1, etc}
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Creator
            $table->integer('sent_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broadcast_notifications');
    }
};
