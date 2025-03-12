<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->text('content');
            $table->enum('status', ['unread', 'read','deleted'])->default('unread');
            $table->timestamps();

            // Optional fields
            $table->string('subject')->nullable();
            $table->string('attachment')->nullable();
            $table->enum('message_type', ['text', 'image', 'file', 'voice'])->default('text');
            $table->boolean('is_urgent')->default(false);
            $table->unsignedBigInteger('conversation_id');//->nullable();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
