<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReplaceIdStationWithStationIdInMetDataTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('met_data_preview', function (Blueprint $table) {
            $table->renameColumn('id_station', 'station_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('met_data_preview', function (Blueprint $table) {
            $table->renameColumn('station_id', 'id_station');
        });
    }
}
