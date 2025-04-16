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
        Schema::create('atm_banks_transaction_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banks_transactions_id')->nullable();
            $table->string('employee_id')->nullable(); // Changed to string
            $table->dateTime('date_approved')->nullable();
            $table->unsignedBigInteger('user_groups_id')->nullable();
            $table->integer('sequence_no')->nullable();
            $table->unsignedBigInteger('transaction_actions_id')->nullable();

            $table->enum('status', ['Completed', 'Pending', 'Stand By', 'Cancelled', 'Open to Others'])->nullable();
            $table->enum('type', ['Received', 'Released'])->nullable();

            $table->foreign('employee_id')->references('employee_id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('banks_transactions_id')->references('id')->on('atm_banks_transactions')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('atm_banks_transaction_approvals');
    }
};
