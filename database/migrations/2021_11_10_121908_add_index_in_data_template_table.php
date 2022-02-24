<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexInDataTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('met_data_preview', function (Blueprint $table) {
            $table->index(array('uploader_id', 'fecha_hora', 'station_id'), 'met_data_preview_summary_index');
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
            $table->dropIndex('met_data_preview_summary_index');
        });
    }
}
