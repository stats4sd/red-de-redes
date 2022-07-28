<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileIdToMetData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('met_data', function (Blueprint $table) {
            # which file did the record come from?
            $table->unsignedInteger('file_id')->nullable();

            # For the re-upload in March 2022, some entries could have come from multiple files. To help sort and identify, also have a temporary json file_ids_for_sorting:
            $table->json('file_ids_for_sorting')->nullable()->comment('temporary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('met_data', function (Blueprint $table) {
            $table->dropColumn('file_id');
            $table->dropColumn('file_ids_for_sorting');
        });

    }
}
