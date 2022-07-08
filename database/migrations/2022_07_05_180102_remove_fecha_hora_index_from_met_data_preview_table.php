<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFechaHoraIndexFromMetDataPreviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // from https://stackoverflow.com/questions/45882990/how-can-indexes-be-checked-if-they-exist-in-a-laravel-migration

        Schema::table('met_data_preview', function (Blueprint $table) {

            // check if the index exists
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes('met_data_preview');

            if (array_key_exists("fecha_hora", $indexesFound)) {
                $table->dropIndex("fecha_hora");
            }
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
            //
        });
    }
}
