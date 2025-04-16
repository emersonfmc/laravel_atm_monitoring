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

            $table->string('pension_number')->nullable();
            $table->string('pension_type')->nullable();
            $table->enum('account_type', ['SSS', 'GSIS'])->nullable();

            $table->string('transaction_number')->nullable();

            $table->enum('atm_type',['ATM','Passbook','Sim Card'])->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->integer('pin_no')->nullable();
            $table->enum('atm_status',['old','new'])->nullable();
            $table->date('expiration_date')->nullable();

            $table->string('collection_date')->nullable();
            $table->integer('cash_box_no')->nullable();
            $table->integer('safekeep_cash_box_no')->nullable();
            $table->integer('replacement_count')->default(0);

            $table->enum('location',['Branch','Head Office','Released','Safekeep'])->nullable();
            $table->enum('status',['0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18'])
                    ->default('1')
                    ->comment('0 = Release
                               1 = Active
                               2 = Release to Client
                               3 = Released ATM to client and Become Return Client but in Another ATM
                               4 = Return Client Same ATM
                               5 = Old ATM Did Not Return by Bank
                               6 = Safekeep
                               7 = Cancelled Loan');

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
