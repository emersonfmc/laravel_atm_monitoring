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
        Schema::create('atm_client_banks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_information_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('transaction_number')->unique()->nullable();

            $table->enum('atm_type',['ATM','Passbook','Sim Card'])->nullable();
            $table->string('bank_account_no')->unique()->nullable();
            $table->string('bank_name')->nullable();
            $table->integer('pin_no')->nullable();
            $table->enum('atm_status',['old','new'])->nullable();
            $table->date('expiration_date')->nullable();

            $table->string('collection_date')->nullable();
            $table->integer('cash_box_no')->nullable();
            $table->integer('safekeep_cash_box_no')->nullable();
            $table->integer('replacement_count')->default(0);

            $table->enum('location',['Branch','Head Office','Released','Safekeep'])->nullable();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('client_information_id')->references('id')->on('client_information')->onDelete('set null')->onUpdate('cascade');

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
        Schema::dropIfExists('atm_client_banks');
    }
};
