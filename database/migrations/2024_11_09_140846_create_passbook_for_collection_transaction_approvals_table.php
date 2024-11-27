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
        Schema::create('passbook_for_collection_transaction_approvals', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('passbook_transactions_id')->nullable();
            $table->string('employee_id')->nullable(); // Changed to string
            $table->dateTime('date_approved')->nullable();
            $table->unsignedBigInteger('user_groups_id')->nullable();
            $table->integer('sequence_no')->nullable();
            $table->unsignedBigInteger('transaction_actions_id')->nullable();

            $table->enum('status', ['Completed','Pending','Stand By','Cancelled','Returning to Branch'])->nullable();
            $table->enum('type', ['Received', 'Released'])->nullable();

            $table->foreign('employee_id','employee_id')->references('employee_id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('passbook_transactions_id','passbook_transactions_id')->references('id')->on('passbook_for_collection_transactions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_groups_id','user_groups_id')->references('id')->on('data_user_groups')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('transaction_actions_id','transaction_actions_id')->references('id')->on('data_transaction_actions')->onDelete('set null')->onUpdate('cascade');

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
        Schema::dropIfExists('passbook_for_collection_transaction_approvals');
    }
};
