<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoFlagsToFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->boolean('is_marked_as_reviewed')->nullable()->comment('A flag to indicate whether this file is marked as reviewed');
            $table->boolean('is_marked_to_remove')->nullable()->comment('A flag to indicate whether this file is marked to remove');

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
            $table->dropColumn('is_marked_as_reviewed');
            $table->dropColumn('is_marked_to_remove');
        });
    }
}
