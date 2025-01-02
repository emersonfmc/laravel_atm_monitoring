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
        Schema::create('documents_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('request_number')->nullable();
            $table->string('request_by_employee_id')->nullable();

            $table->string('cancelled_by_employee_id')->nullable();
            $table->dateTime('cancelled_date')->nullable();
            $table->string('remarks')->nullable();

            $table->enum('status',['On Going','Cancelled','Completed'])->nullable();
            $table->foreign('request_by_employee_id')
                    ->references('employee_id')
                    ->on('users')
                    ->onDelete('set null')
                    ->onUpdate('cascade');

            $table->foreign('branch_id')
                    ->references('id')
                    ->on('branches')
                    ->onDelete('set null')
                    ->onUpdate('cascade');

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
        Schema::dropIfExists('documents_transactions');
    }
};
