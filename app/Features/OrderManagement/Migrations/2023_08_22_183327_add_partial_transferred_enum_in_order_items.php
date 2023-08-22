<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPartialTransferredEnumInOrderItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            DB::statement("ALTER TABLE `order_items` CHANGE `state` `state` ENUM('CREATED','PARTIAL_MAPPED','MAPPED','PARTIAL_TRANSFERRED','TRANSFERRED','CANCELLED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CREATED';");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->enum('state', ["CREATED", "PARTIAL_MAPPED", "MAPPED", "TRANSFERRED", "CANCELLED"])->default("CREATED");
        });
    }
}
