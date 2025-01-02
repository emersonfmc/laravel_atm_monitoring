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
        Schema::create('documents_transaction_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('documents_transactions_id')->nullable();
            $table->string('employee_id')->nullable(); // Changed to string
            $table->dateTime('date_approved')->nullable();
            $table->unsignedBigInteger('user_groups_id')->nullable();
            $table->integer('sequence_no')->nullable();
            $table->unsignedBigInteger('transaction_actions_id')->nullable();

            $table->enum('status', ['Completed','Pending','Stand By','Cancelled'])->nullable();
            $table->enum('type', ['Received', 'Released'])->nullable();

            $table->foreign('employee_id')->references('employee_id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('documents_transactions_id','documents_transactions_id')->references('id')->on('documents_transactions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_groups_id')->references('id')->on('data_user_groups')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('transaction_actions_id')->references('id')->on('data_transaction_actions')->onDelete('set null')->onUpdate('cascade');

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
        Schema::dropIfExists('documents_transaction_approvals');
    }
};
