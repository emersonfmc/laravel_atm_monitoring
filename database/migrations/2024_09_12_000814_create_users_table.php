<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('employee_id')->unique();
            $table->string('contact_no')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            $table->string('username')->nullable();
            $table->string('password');

            $table->enum('session',['Online','Offline'])->default('Offline');
            $table->enum('user_types',['Developer','Admin','District','Area','Branch','Head Office'])->nullable();

            $table->string('avatar')->nullable();
            $table->date('dob');
            $table->rememberToken();

            $table->unsignedBigInteger('user_group_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('district_code_id')->nullable();
            $table->unsignedBigInteger('area_code_id')->nullable();

            $table->enum('status',['Active','Inactive'])->default('Active');

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
        Schema::dropIfExists('users');
    }
}
