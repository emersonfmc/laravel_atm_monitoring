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
            $table->string('employee_id')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();

            $table->timestamp('email_verified_at')->nullable();

            $table->string('username')->nullable();
            $table->string('password')->default('$2y$12$Y0/wCO5ghJDL.DHsRaxOyOpByhTEv9z03pxZEcsKJjgh1JZL1Vn36'); // default as password

            $table->string('user_session')->nullable();
            $table->enum('user_types',['Administrator','District','Area','Branch','Head Office'])->nullable();

            $table->string('avatar')->nullable();
            $table->date('dob')->nullable();
            $table->rememberToken();

            $table->json('position')->nullable(); // array

            $table->unsignedBigInteger('user_system_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable()->default('2');

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
