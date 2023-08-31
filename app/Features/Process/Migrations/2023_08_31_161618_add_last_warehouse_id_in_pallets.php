<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastWarehouseIdInPallets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pallets', function (Blueprint $table) {
            $table->foreignId('warehouse_id')
                ->comment('last warehouse location')
                ->nullable()
                ->after('order_id');

            $table->foreign('warehouse_id')->on('warehouses')->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pallets', function (Blueprint $table) {
            $table->dropForeign('pallets_warehouse_id_foreign');
            $table->dropColumn('warehouse_id');
        });
    }
}
