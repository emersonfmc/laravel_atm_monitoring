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
        Schema::create('atm_transaction_approvals_balance_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trans_approvals_id')->nullable();
            $table->unsignedBigInteger('check_by_user_id')->nullable();
            $table->integer('balance')->default(0);
            $table->string('remarks')->nullable();

            // Manually set shorter names for the foreign keys
            $table->foreign('trans_approvals_id', 'trans_approvals_id')
                ->references('id')->on('atm_banks_transaction_approvals')
                ->onDelete('set null')->onUpdate('cascade');

            $table->foreign('check_by_user_id', 'check_by_user_id')
                ->references('id')->on('users')
                ->onDelete('set null')->onUpdate('cascade');

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
        Schema::dropIfExists('atm_transaction_approvals_balance_logs');
    }
};
