<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFilesTableWithObservationFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files', function(Blueprint $table) {
            $table->dropColumn('observation_id');
            $table->string('observation_file')->nullable()->comment('path to observation file');
            $table->string('observation_file_name')->nullable()->comment('original name of observation file');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('files', function(Blueprint $table) {
            $table->foreignId('observation_id');
            $table->dropColumn('observation_file');
            $table->dropColumn('observation_file_name');

        });
    }
}
