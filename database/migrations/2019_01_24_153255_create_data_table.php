<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('fecha_hora')->nullable();
            $table->integer('intervalo')->nullable();
            $table->decimal('temperatura_interna')->nullable();
            $table->integer('humedad_interna')->nullable();  
            $table->decimal('temperatura_externa')->nullable();
            $table->integer('humedad_externa')->nullable(); 
            $table->decimal('presion_relativa')->nullable();
            $table->decimal('presion_absoluta')->nullable();
            $table->decimal('velocidad_viento')->nullable();
            $table->decimal('sensacion_termica')->nullable(); 
            $table->decimal('rafaga')->nullable();
            $table->string('direccion_del_viento')->nullable();
            $table->decimal('punto_rocio')->nullable(); 
            $table->decimal('lluvia_hora')->nullable(); 
            $table->decimal('lluvia_24_horas')->nullable();
            $table->decimal('lluvia_semana')->nullable();
            $table->decimal('lluvia_mes')->nullable();
            $table->decimal('lluvia_total')->nullable();
            $table->decimal('hi_temp')->nullable(); 
            $table->decimal('low_temp')->nullable();
            $table->decimal('wind_cod')->nullable();
            $table->decimal('wind_run')->nullable(); 
            $table->decimal('hi_speed')->nullable(); 
            $table->string('hi_dir')->nullable(); 
            $table->decimal('wind_cod_dom')->nullable();
            $table->decimal('wind_chill')->nullable();
            $table->decimal('index_heat')->nullable();
            $table->decimal('index_thw')->nullable();
            $table->decimal('index_thsw')->nullable();
            $table->decimal('rain')->nullable(); 
            $table->decimal('solar_rad')->nullable();
            $table->decimal('solar_energy')->nullable();
            $table->decimal('radsolar_max')->nullable();
            $table->decimal('uv_index')->nullable();
            $table->decimal('uv_dose')->nullable();
            $table->decimal('uv_max')->nullable();
            $table->decimal('heat_days_d')->nullable();
            $table->decimal('cool_days_d')->nullable();
            $table->decimal('in_dew')->nullable(); 
            $table->decimal('in_heat')->nullable();
            $table->decimal('in_emc')->nullable(); 
            $table->decimal('in_air_density')->nullable();
            $table->decimal('evapotran')->nullable(); 
            $table->decimal('soil_1_moist')->nullable();
            $table->decimal('soil_2_moist')->nullable();
            $table->decimal('leaf_wet1')->nullable(); 
            $table->decimal('wind_samp')->nullable();
            $table->decimal('wind_tx')->nullable();
            $table->decimal('iss_recept')->nullable();     
            $table->integer('id_station')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data');
    }
}
