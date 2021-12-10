<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnWidthInDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data', function (Blueprint $table) {
            // change heat_days_d from 2 d.p. to 3 d.p.
            $table->decimal('heat_days_d', 9, 3)->nullable()->change();

            // change heat_days_d from 2 d.p. to 4 d.p.
            $table->decimal('in_air_density', 10, 4)->nullable()->change();
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
            // decimal is 2 d.p. by default
            $table->decimal('heat_days_d')->nullable()->change();
            $table->decimal('in_air_density')->nullable()->change();
        });
    }
}
