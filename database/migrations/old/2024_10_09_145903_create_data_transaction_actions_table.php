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
        Schema::create('data_transaction_actions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('transaction_type',['Going to Head Office','Going to Branch Office','Outside For Collection'])->nullable();
            $table->integer('transaction')->nullable();
            $table->enum('atm_type',['ATM','Passbook','Sim Card'])->nullable();
            $table->enum('status',['Active','Inactive'])->nullable();
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
        Schema::dropIfExists('data_transaction_actions');
    }
};
