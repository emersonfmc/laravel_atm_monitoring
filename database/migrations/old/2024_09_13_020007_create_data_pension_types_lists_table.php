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
        Schema::create('data_pension_types_lists', function (Blueprint $table) {
            $table->id();
            $table->string('pension_name', 255)->nullable();  // Ensure this matches in client_information
            $table->enum('types', ['SSS', 'GSIS'])->nullable();
            $table->enum('status', ['Active', 'Inactive'])->nullable();
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
        Schema::dropIfExists('data_pension_types_lists');
    }
};
