<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReachTrucksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reach_trucks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pallet_id');
            $table->morphs('from_locationable');
            $table->nullableMorphs('to_locationable');
            $table->boolean('is_transfered')->default(false);
            $table->unsignedBigInteger('transfered_by')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('pallet_id')->on('pallets')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('transfered_by')->on('users')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->on('users')->references('id')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reach_trucks');
    }
}
