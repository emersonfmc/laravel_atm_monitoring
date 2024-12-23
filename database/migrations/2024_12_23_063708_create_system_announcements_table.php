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
        Schema::create('system_announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('employee_id')->nullable(); // Changed to string
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->enum('type', ['New Features','Enhancements','Maintenance','Notification'])->nullable();
            $table->foreign('employee_id','employee_id')->references('employee_id')->on('users')->onDelete('set null')->onUpdate('cascade');
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
        Schema::dropIfExists('system_announcements');
    }
};
