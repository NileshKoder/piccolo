<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLineToLoadingEnumInLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loading_enum_in_locations', function (Blueprint $table) {
            DB::statement("ALTER TABLE `locations` CHANGE `type` `type` ENUM('GLASS','CERAMIC','RECYCLE','LINES','CURING','LOADING','LINE_TO_WH','LINE_TO_LOADING', 'WH_TO_LINE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loading_enum_in_locations', function (Blueprint $table) {
            DB::statement("ALTER TABLE `locations` CHANGE `type` `type` ENUM('GLASS','CERAMIC','RECYCLE','LINES','CURING','LOADING','LINE_TO_WH') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
        });
    }
}
