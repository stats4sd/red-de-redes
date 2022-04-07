<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoordinatesToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meteobridge', function (Blueprint $table) {
            $table->decimal('latitude', 9,6)->after('station_id');
            $table->decimal('longitude', 9,6)->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meteobridge', function (Blueprint $table) {
            //
        });
    }
}
