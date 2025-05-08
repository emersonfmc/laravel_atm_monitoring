<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elm_transaction_sequences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('action_id');
            $table->integer('sequence_no');
            $table->unsignedBigInteger('user_group_id');

            $table->enum('type',['Received','Released'])->nullable();
            $table->string('remarks')->nullable();
            $table->string('oc_receiver_type')->nullable();

            $table->foreign('action_id')->references('id')->on('elm_transaction_actions')->onDelete('restrict')->onUpdate('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('elm_transaction_sequences');
    }
};
