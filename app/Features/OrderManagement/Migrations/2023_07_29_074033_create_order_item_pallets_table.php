<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemPalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_item_pallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_item_id');
            $table->unsignedBigInteger('pallet_id');
            $table->unsignedBigInteger('pallet_detail_id');
            $table->double('weight', 15, 2);
            $table->timestamps();

            $table->foreign('order_item_id')->on('order_items')->references('id');
            $table->foreign('pallet_id')->on('pallets')->references('id');
            $table->foreign('pallet_detail_id')->on('pallet_details')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_item_pallets');
    }
}
