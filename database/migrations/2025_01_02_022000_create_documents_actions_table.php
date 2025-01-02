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
        Schema::create('documents_actions', function (Blueprint $table) {
            $table->id();
            $table->string('document')->nullable();
            $table->string('sequence_type')->nullable();
            $table->string('return_no')->nullable();
            $table->string('option_type')->nullable();
            $table->enum('document_session', ['1','2','3','4'])->nullable()->comment('2 - Branch Access, 3 - HO Access, 4 - HO to HO');
            $table->string('department_code')->nullable();
            $table->enum('status', ['Active','Inactive'])->default('Active');
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
        Schema::dropIfExists('documents_actions');
    }
};
