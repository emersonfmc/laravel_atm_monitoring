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
        Schema::create('atm_client_banks_transactions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('client_banks_id')->nullable();
            $table->unsignedBigInteger('transaction_actions_id')->nullable();
            $table->unsignedBigInteger('request_by_user_id')->nullable();

            $table->string('transaction_number')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->enum('atm_type',['ATM','Passbook','Sim Card'])->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->integer('aprb_no')->nullable();

            $table->string('reason')->nullable();
            $table->string('reason_remarks')->nullable();

            $table->integer('yellow_copy')->nullable();
            $table->unsignedBigInteger('released_client_images_id')->nullable();

            $table->foreign('client_banks_id')->references('id')->on('atm_client_banks')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('transaction_actions_id')->references('id')->on('atm_transaction_actions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('request_by_user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('released_client_images_id')->references('id')->on('atm_released_client_images')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('atm_client_banks_transactions');
    }
};
