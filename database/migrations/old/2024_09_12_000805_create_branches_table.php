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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();

            $table->string('branch_abbreviation')->nullable();
            $table->string('branch_location')->nullable();
            $table->string('branch_head')->nullable();

            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->unsignedBigInteger('company_id');

            $table->foreign('district_id')->references('id')->on('data_districts')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('area_id')->references('id')->on('data_areas')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('restrict')->onUpdate('cascade');

            $table->enum('status',['Active','Inactive'])->nullable();

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
        Schema::dropIfExists('branches');
    }
};
