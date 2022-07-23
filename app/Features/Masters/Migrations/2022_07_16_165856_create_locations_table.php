<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('abbr', 5);
            $table->enum('type', ['GLASS', 'CERAMIC', 'RECYCLE', 'LINES', 'CURING', 'LOADING']);
            $table->timestamps();

            $table->unique(['name', 'abbr']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
