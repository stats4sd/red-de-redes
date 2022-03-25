<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnlargeColumnWidthInSummaryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tendays_met_data', function (Blueprint $table) {
            // change lluvia_24_horas_total from decimal(8,2) to decimal(9,2)
            $table->decimal('lluvia_24_horas_total', 9, 2)->nullable()->change();
        });

        Schema::table('monthly_met_data', function (Blueprint $table) {
            // change lluvia_24_horas_total from decimal(8,2) to decimal(10,2)
            $table->decimal('lluvia_24_horas_total', 10, 2)->nullable()->change();
        });

        Schema::table('yearly_met_data', function (Blueprint $table) {
            // change lluvia_24_horas_total from decimal(8,2) to decimal(11,2)
            $table->decimal('lluvia_24_horas_total', 11, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tendays_met_data', function (Blueprint $table) {
            // fallback lluvia_24_horas_total from decimal(9,2) to decimal(8,2)
            $table->decimal('lluvia_24_horas_total', 8, 2)->nullable()->change();
        });

        Schema::table('monthly_met_data', function (Blueprint $table) {
            // fallback lluvia_24_horas_total from decimal(10,2) to decimal(8,2)
            $table->decimal('lluvia_24_horas_total', 8, 2)->nullable()->change();
        });

        Schema::table('yearly_met_data', function (Blueprint $table) {
            // fallback lluvia_24_horas_total from decimal(11,2) to decimal(8,2)
            $table->decimal('lluvia_24_horas_total', 8, 2)->nullable()->change();
        });
    }
}
