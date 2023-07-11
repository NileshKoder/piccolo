<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('sku_code_id');
            $table->unsignedBigInteger('variant_id');
            $table->unsignedBigInteger('location_id');
            $table->double('required_weight', 15, 2);
            $table->date('pick_up_date');
            $table->enum('state', ["CREATED", "PARTIAL_MAPPED", "MAPPED", "TRANSFERED", "CANCELLED"])->default("CREATED");
            $table->timestamps();

            $table->foreign('order_id')->on('orders')->references('id');
            $table->foreign('sku_code_id')->on('sku_codes')->references('id');
            $table->foreign('variant_id')->on('variants')->references('id');
            $table->foreign('location_id')->on('locations')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
