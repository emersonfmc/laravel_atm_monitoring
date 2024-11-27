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
        Schema::create('passbook_for_collection_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_banks_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('request_number')->nullable();
            $table->string('reference_no')->nullable();
            $table->string('request_by_employee_id')->nullable();

            $table->string('scan_by_employee_id')->nullable();
            $table->integer('scan_status')->nullable();
            $table->string('remarks')->nullable();
            $table->string('cancelled_by_employee_id')->nullable();
            $table->dateTime('cancelled_date')->nullable();

            $table->enum('status',['On Going','Cancelled','Completed','Returning to Branch'])->nullable();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('client_banks_id')->references('id')->on('atm_client_banks')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('request_by_employee_id', 'request_by_employee_id')->references('employee_id')->on('users')->onDelete('set null')->onUpdate('cascade');

            $table->foreign('scan_by_employee_id')->references('employee_id')->on('users')->onDelete('set null')->onUpdate('cascade');
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
        Schema::dropIfExists('passbook_for_collection_transactions');
    }
};
