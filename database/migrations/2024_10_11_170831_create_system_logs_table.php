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
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('module',['ATM / PB Monitoring','ATM Monitoring','PB Monitoring','Document Monitoring','Inventory','Settings'])->nullable();
            $table->enum('action',['Create','Update','Delete'])->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->json('description_logs')->nullable();
            $table->string('employee_id')->nullable();
            $table->ipAddress('ip_address');
            $table->unsignedBigInteger('company_id');

            $table->foreign('employee_id')->references('employee_id')->on('users')->onDelete('set null')->onUpdate('cascade');
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
        Schema::dropIfExists('system_logs');
    }
};
