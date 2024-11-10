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
        Schema::create('passbook_released_client_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('passbook_transactions_id')->nullable();
            $table->string('filename')->nullable();
            $table->string('image_name')->nullable();
            $table->foreign('passbook_transactions_id')->references('id')->on('passbook_for_collection_transactions')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('passbook_released_client_images');
    }
};
