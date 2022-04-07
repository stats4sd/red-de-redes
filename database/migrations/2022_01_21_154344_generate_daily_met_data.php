<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GenerateDailyMetData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // For correct indentation in MySQL stored procedure to be created,
        // MySQL program source code needs to be stored in below format

        $procedure =         
"
DROP PROCEDURE IF EXISTS `generate_daily_met_data`;

CREATE PROCEDURE `generate_daily_met_data`(IN id_date VARCHAR(10), IN iv_station_id INT)
BEGIN

/*
	Stored procedure for generating daily_met_data record for a specific date and a specific station
*/

	DECLARE expected_number_of_records INT;
	
	-- assume met station performs measurement every 15 minutes
	-- number of records per day = 4 * 24 = 96
	SET expected_number_of_records = 96;

	-- delete daily_met_data record for a specfic date and a specific station
	DELETE FROM daily_met_data
	WHERE fecha = id_date
	AND station_id = iv_station_id;
	
	-- generate daily_met_data record by summarizing all records for a specific date and a specific station
	INSERT INTO daily_met_data 
	(fecha, station_id, 
	max_temperatura_interna, min_temperatura_interna, avg_temperatura_interna,
	max_humedad_interna, min_humedad_interna, avg_humedad_interna,
	max_temperatura_externa, min_temperatura_externa, avg_temperatura_externa,
	max_humedad_externa, min_humedad_externa, avg_humedad_externa,
	max_presion_relativa, min_presion_relativa, avg_presion_relativa, 
	max_presion_absoluta, min_presion_absoluta, avg_presion_absoluta, 
	max_velocidad_viento, min_velocidad_viento, avg_velocidad_viento, 
	max_sensacion_termica, min_sensacion_termica, avg_sensacion_termica, 
	lluvia_24_horas_total,
	actual_no_of_records, expected_no_of_records,
	created_at, updated_at)
	SELECT 
	DATE_FORMAT(fecha_hora, '%Y-%m-%d'), station_id,
	MAX(temperatura_interna), MIN(temperatura_interna), AVG(temperatura_interna),
	MAX(humedad_interna), MIN(humedad_interna), AVG(humedad_interna),
	MAX(temperatura_externa), MIN(temperatura_externa), AVG(temperatura_externa),
	MAX(humedad_externa), MIN(humedad_externa), AVG(humedad_externa),
	MAX(presion_relativa), MIN(presion_relativa), AVG(presion_relativa),
	MAX(presion_absoluta), MIN(presion_absoluta), AVG(presion_absoluta),
	MAX(velocidad_viento), MIN(velocidad_viento), AVG(velocidad_viento),
	MAX(sensacion_termica), MIN(sensacion_termica), AVG(sensacion_termica),
	SUM(lluvia_total), 
	COUNT(*), expected_number_of_records,
	NOW(), NOW()
	FROM met_data
	WHERE fecha_hora BETWEEN 
	id_date AND DATE_ADD(DATE_ADD(id_date, INTERVAL 1 DAY), INTERVAL -1 SECOND)
	AND station_id = iv_station_id
	GROUP BY DATE_FORMAT(fecha_hora, '%Y-%m-%d'), station_id;
	
END
";

        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `generate_daily_met_data` ";

        DB::unprepared($procedure);
    }
}
