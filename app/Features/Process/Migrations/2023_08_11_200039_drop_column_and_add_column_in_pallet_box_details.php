<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnAndAddColumnInPalletBoxDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pallet_box_details', function (Blueprint $table) {
            $table->dropForeign('pallet_box_details_box_id_foreign');
            $table->dropColumn('box_id');
            $table->string('box_name')->after('pallet_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pallet_box_details', function (Blueprint $table) {
            $table->dropColumn('box_name');
            $table->foreignId('box_id');
            $table->foreign('box_id')->on('boxes')->references('id');
        });
    }
}
