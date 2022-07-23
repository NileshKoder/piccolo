<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePalletDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pallet_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pallet_id');
            $table->foreignId('sku_code_id');
            $table->foreignId('variant_id');
            $table->double('weight', 5, 2);
            $table->string('batch', 25);


            $table->foreign('pallet_id')->on('pallets')->references('id');
            $table->foreign('sku_code_id')->on('sku_codes')->references('id');
            $table->foreign('variant_id')->on('variants')->references('id');
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
        Schema::dropIfExists('pallet_details');
    }
}
