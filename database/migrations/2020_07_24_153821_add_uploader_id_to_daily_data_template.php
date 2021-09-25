<?php

use Illuminate\Database\Migrations\Migration;

class AddUploaderIdToDailyDataTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS daily_data_preview");
        DB::statement("
            CREATE VIEW daily_data_preview AS
            SELECT

                -- ######### START WITH THE GROUP-BY FIELDS
                -- ## We want 'daily'
                -- ## Grouped by weather-station
                max(`data_template`.`id`) as `id`,
                LEFT(fecha_hora,10) as fecha,
                id_station as id_station,
                uploader_id as uploader_id,

                -- #########################################
                -- #########################################

                -- ################# Temperature and Humidity
                -- ## Min, Max and Avg

                MAX(temperatura_interna) as max_temperatura_interna,
                MIN(temperatura_interna) as min_temperatura_interna,
                AVG(temperatura_interna) as avg_temperatura_interna,

                MAX(humedad_interna) as max_humedad_interna,
                MIN(humedad_interna) as min_humedad_interna,
                AVG(humedad_interna) AS avg_humedad_interna,

                MAX(temperatura_externa) as max_temperatura_externa,
                MIN(temperatura_externa) as min_temperatura_externa,
                AVG(temperatura_externa) as avg_temperatura_externa,

                MAX(humedad_externa) as max_humedad_externa,
                MIN(humedad_externa) as min_humedad_externa,
                AVG(humedad_externa) as avg_humedad_externa,

                -- ################# Pressure
                -- ## Min, Max and Avg

                MAX(presion_relativa) as max_presion_relativa,
                MIN(presion_relativa) as min_presion_relativa,
                AVG(presion_relativa) as avg_presion_relativa,

                MAX(presion_absoluta) as max_presion_absoluta,
                MIN(presion_absoluta) as min_presion_absoluta,
                AVG(presion_absoluta) as avg_presion_absoluta,

                -- ################# Wind Speed
                -- ## Min, Max and Avg

                MAX(velocidad_viento) as max_velocidad_viento,
                MIN(velocidad_viento) as min_velocidad_viento,
                AVG(velocidad_viento) as avg_velocidad_viento,


                -- ################# Heat Sensation?
                -- ## Min, Max and Avg

                MAX(sensacion_termica) as max_sensacion_termica,
                MIN(sensacion_termica) as min_sensacion_termica,
                AVG(sensacion_termica) as avg_sensacion_termica,

                -- ################# Rainfall
                -- ## Rainfall is already totalled per hour, day and week.
                -- ## So, for days we can just use the 24_horas column.

                MAX(lluvia_24_horas) as lluvia_24_horas_total


                FROM data_template
                GROUP BY fecha, id_station, uploader_id;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS daily_data_preview");
    }
}
