<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemPalletDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_item_pallet_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_item_id');
            $table->string('pallet_name');
            $table->double('weight_in_pallet', 15, 2);
            $table->double('mapped_weight', 15, 2);
            $table->string('transfered_by')->nullable();
            $table->dateTime('transfered_at')->nullable();
            $table->timestamps();

            $table->foreign('order_item_id')->on('order_items')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_item_pallet_details');
    }
}
