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
        Schema::create('data_districts', function (Blueprint $table) {
            $table->id();
            $table->string('district_name')->nullable();
            $table->string('district_number')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->string('status')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('data_districts');
    }
};
