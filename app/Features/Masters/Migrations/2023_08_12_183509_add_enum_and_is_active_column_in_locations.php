<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnumAndIsActiveColumnInLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            DB::statement("ALTER TABLE `locations` CHANGE `type` `type` ENUM('GLASS','CERAMIC','RECYCLE','LINES','CURING','LOADING','LINE_TO_WH') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
            $table->enum('state', ['ACTIVE', 'INACTIVE'])->default('ACTIVE')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            DB::statement("ALTER TABLE `locations` CHANGE `type` `type` ENUM('GLASS','CERAMIC','RECYCLE','LINES','CURING','LOADING') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
            $table->dropColumn('state');
        });
    }
}
