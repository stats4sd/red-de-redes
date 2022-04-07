<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetSummaryDailyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('met_summary_daily', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->foreignId('station_id');
            $table->decimal('max_temperatura_interna')->nullable();
            $table->decimal('min_temperatura_interna')->nullable();
            $table->decimal('avg_temperatura_interna')->nullable();

            $table->integer('max_humedad_interna')->nullable();
            $table->integer('min_humedad_interna')->nullable();
            $table->integer('avg_humedad_interna')->nullable();

            $table->decimal('max_temperatura_externa')->nullable();
            $table->decimal('min_temperatura_externa')->nullable();
            $table->decimal('avg_temperatura_externa')->nullable();

            $table->integer('max_humedad_externa')->nullable();
            $table->integer('min_humedad_externa')->nullable();
            $table->integer('avg_humedad_externa')->nullable();

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
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('met_summary_daily');
    }
}
