<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMeteobridgeColumnToData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data', function (Blueprint $table) {
            $table->tinyInteger('meteobridge')->default(0);
            $table->decimal('meteobridge_latitude', 9,6)->after('station_id');
            $table->decimal('meteobridge_longitude', 9,6)->after('meteobridge_latitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data', function (Blueprint $table) {
            //
        });
    }
}
