<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSuffixToQrcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qrcodes', function (Blueprint $table) {
            $table->string('suffix', 50)->nullable()->after('prefix');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qrcodes', function (Blueprint $table) {
            $table->dropColumn('suffix');
        });
    }
}
