<?php

use Illuminate\Database\Migrations\Migration;

class DataView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS weather_data");
        DB::statement("
            CREATE VIEW weather_data AS
            SELECT

                `data`.`id` as `id`,
                DATE(fecha_hora) as fecha,
                TIME(fecha_hora) as hora,
                stations.label as station,
                id_station as station_id,
                intervalo,
                temperatura_interna,
                humedad_interna,
                humedad_externa,
                presion_relativa,
                presion_absoluta,
                velocidad_viento,
                sensacion_termica,
                rafaga,
                direccion_del_viento,
                punto_rocio,
                lluvia_hora,
                lluvia_24_horas,
                lluvia_semana,
                lluvia_mes,
                lluvia_total,
                hi_temp,
                low_temp,
                wind_cod,
                wind_run,
                hi_speed,
                hi_dir,
                wind_cod_dom,
                wind_chill,
                index_heat,
                index_thw,
                index_thsw,
                rain,
                solar_rad,
                solar_energy,
                radsolar_max,
                uv_index,
                uv_dose,
                uv_max,
                heat_days_d,
                cool_days_d,
                in_dew,
                in_heat,
                in_emc,
                in_air_density,
                evapotran,
                soil_1_moist,
                soil_2_moist,
                leaf_wet1,
                leaf_wet2,
                leaf_wet3,
                leaf_wet4,
                wind_samp,
                wind_tx,
                iss_recept

                FROM data

                LEFT JOIN stations ON stations.id = id_station;

        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS weather_data");
    }
}
