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
        Schema::table('data_template', function (Blueprint $table) {
            $table->index(array('uploader_id', 'fecha_hora', 'id_station'), 'data_template_summary_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_template', function (Blueprint $table) {
            $table->dropIndex('data_template_summary_index');
        });
    }
}
