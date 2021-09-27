<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddObservationIdToDataAndDataTemplateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data', function (Blueprint $table) {
            $table->foreignId('observation_id')->nullable()->after('station_id');
        });
        Schema::table('data_template', function (Blueprint $table) {
            $table->foreignId('observation_id')->nullable()->after('station_id');
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
            $table->dropForeign(['observation_id']);
            $table->dropColumn('observation_id');
        });

        Schema::table('data_template', function (Blueprint $table) {
            $table->dropForeign(['observation_id']);
            $table->dropColumn('observation_id');
        });
    }
}
