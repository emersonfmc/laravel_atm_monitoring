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
        Schema::create('elm_documents_sequences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('documents_actions_id');
            $table->integer('sequence_no');
            $table->unsignedBigInteger('user_group_id');
            $table->enum('type',['Received','Released'])->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elm_documents_sequences');
    }
};
