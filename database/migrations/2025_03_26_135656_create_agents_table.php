<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade'); // Links to users table
            $table->string('country')->nullable();
            $table->string('NIN')->nullable()->unique();
            $table->string('document_attachment_1_name')->nullable();
            $table->string('document_attachment_2_name')->nullable();
            $table->string('document_attachment_3_name')->nullable();
            $table->string('document_attachment_1')->nullable();
            $table->string('document_attachment_2')->nullable();
            $table->string('document_attachment_3')->nullable();
            $table->string('signed_agreement_form')->nullable();
            $table->string('agent_code')->nullable()->unique();
            $table->date('request_date')->nullable();
            $table->enum('registration_status', ['step_1', 'step_2', 'step_3','complete', 'incomplete'])->default('incomplete');
            $table->enum('status', ['verified', 'unverified'])->default('unverified');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->date('verified_date')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('agents');
    }
};
