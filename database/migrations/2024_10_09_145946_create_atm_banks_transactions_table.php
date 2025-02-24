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
        Schema::create('atm_banks_transactions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('client_banks_id')->nullable();
            $table->unsignedBigInteger('transaction_actions_id')->nullable();
            $table->string('request_by_employee_id')->nullable();
            $table->string('transaction_number')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->integer('aprb_no')->nullable();
            $table->string('reason')->nullable();
            $table->string('reason_remarks')->nullable();
            $table->enum('atm_type',['ATM','Passbook','Sim Card'])->nullable();
            $table->enum('status',['ON GOING','CANCELLED','COMPLETED'])->nullable();

            $table->integer('yellow_copy')->nullable();

            $table->string('oc_request_number')->nullable()->comment('This is for creation of Outside Collection Multiple');
            $table->enum('oc_transaction',['YES','NO'])->default('NO')->comment('If the transaction is Outside Collection it will be YES and NO if not');
            $table->string('oc_request_type')->nullable()->comment('Null if not outside collection, 1 going to bank and 2 returning to HO and 3 For Completed
');
            // $table->unsignedBigInteger('released_client_images_id')->nullable();

            $table->foreign('client_banks_id')->references('id')->on('atm_client_banks')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('transaction_actions_id')->references('id')->on('data_transaction_actions')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('request_by_employee_id')->references('employee_id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null')->onUpdate('cascade');
            // $table->foreign('released_client_images_id')->references('id')->on('atm_released_client_images')->onDelete('set null')->onUpdate('cascade');

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
        Schema::dropIfExists('atm_banks_transactions');
    }
};
