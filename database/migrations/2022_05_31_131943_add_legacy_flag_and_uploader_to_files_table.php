<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLegacyFlagAndUploaderToFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->boolean('is_legacy')->default(0);
            $table->boolean('is_success')->default(0)->comment('was this file upload completed? Kept at false if the upload was aborted by the user.');
            $table->unsignedInteger('uploader_id')->comment('The user who uploaded the file')->nullable();
            $table->string('upload_id')->comment('unique ID to track records through preview and live table');
            $table->unsignedBigInteger('observation_id')->nullable();
            $table->unsignedInteger('new_records_count')->nullable()->comment('how many records in the file are new (not already in the database, using station and timestamp to compare');
            $table->unsignedInteger('duplicate_records_count')->nullable()->comment('how many records in the file already exist in the database, using station and timestamp to compare');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn('is_legacy');
            $table->dropColumn('is_success');
            $table->dropColumn('uploader_id');
            $table->dropColumn('upload_id');
            $table->dropColumn('observation_id');
            $table->dropColumn('new_records_count');
            $table->dropColumn('duplicate_records_count');
        });
    }
}
