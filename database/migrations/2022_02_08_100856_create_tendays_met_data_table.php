<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTendaysMetDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tendays_met_data', function (Blueprint $table) {
            // primray key
            $table->id();

            // year month and station id, requried fields
            $table->integer('year_and_month');
            $table->integer('part');
            $table->integer('station_id')->default('0');

            // aggregated fields, they can be null
            $table->decimal('max_temperatura_interna')->nullable();
            $table->decimal('min_temperatura_interna')->nullable();
            $table->decimal('avg_temperatura_interna')->nullable();

            $table->integer('max_humedad_interna')->nullable();
            $table->integer('min_humedad_interna')->nullable();
            $table->decimal('avg_humedad_interna')->nullable();

            $table->decimal('max_temperatura_externa')->nullable();
            $table->decimal('min_temperatura_externa')->nullable();
            $table->decimal('avg_temperatura_externa')->nullable();

            $table->integer('max_humedad_externa')->nullable();
            $table->integer('min_humedad_externa')->nullable();
            $table->decimal('avg_humedad_externa')->nullable();

            $table->decimal('max_presion_relativa')->nullable();
            $table->decimal('min_presion_relativa')->nullable();
            $table->decimal('avg_presion_relativa')->nullable();

            $table->decimal('max_presion_absoluta')->nullable();
            $table->decimal('min_presion_absoluta')->nullable();
            $table->decimal('avg_presion_absoluta')->nullable();

            $table->decimal('max_velocidad_viento')->nullable();
            $table->decimal('min_velocidad_viento')->nullable();
            $table->decimal('avg_velocidad_viento')->nullable();

            $table->decimal('max_sensacion_termica')->nullable();
            $table->decimal('min_sensacion_termica')->nullable();
            $table->decimal('avg_sensacion_termica')->nullable();

            $table->decimal('lluvia_24_horas_total')->nullable();

            // fields to show raw data completeness
            $table->integer('actual_no_of_records')->nullable();
            $table->integer('expected_no_of_records')->nullable();

            $table->timestamps();

            // add index to speed up query
            $table->index(array('year_and_month', 'part', 'station_id'), 'tendays_met_data_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tendays_met_data');
    }
}
