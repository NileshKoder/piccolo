<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePalletLocationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pallet_location_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pallet_id');
            $table->morphs('locationable');
            $table->foreignId('created_by');
            $table->timestamps();

            $table->foreign('pallet_id')->on('pallets')->references('id');
            $table->foreign('created_by')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pallet_location_logs');
    }
}
