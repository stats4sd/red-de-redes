<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsInStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stations', function (Blueprint $table) {
            $table->dropColumn('filename_map');
            $table->dropColumn('filename_met_station');
            $table->dropColumn('filename_nearby_village');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stations', function (Blueprint $table) {
            $table->string('filename_map')->nullable();
            $table->string('filename_met_station')->nullable();
            $table->string('filename_nearby_village')->nullable();
        });
    }
}
