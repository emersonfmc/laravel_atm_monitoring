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
        Schema::create('atm_transaction_sequences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('atm_transaction_actions_id');
            $table->integer('sequence_no');
            $table->unsignedBigInteger('user_group_id');
            $table->enum('type',['Received','Released'])->nullable();
            $table->foreign('user_group_id')->references('id')->on('data_user_groups')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('atm_transaction_actions_id')->references('id')->on('atm_transaction_actions')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('atm_transaction_sequences');
    }
};
