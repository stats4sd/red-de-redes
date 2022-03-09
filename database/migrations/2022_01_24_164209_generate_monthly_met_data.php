<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GenerateMonthlyMetData extends Migration
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
DROP PROCEDURE IF EXISTS `generate_monthly_met_data`;

CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_monthly_met_data`(IN ii_year INT, IN ii_month INT, IN ii_station_id INT)
BEGIN

/*
	Stored procedure for generating monthly_met_data record for a specific date and a specific station
*/
	
	DECLARE v_from_month_first_day VARCHAR(19);
	DECLARE v_to_month_first_day VARCHAR(19);
	DECLARE i_year_month INT;
	
	DECLARE i_number_of_records_per_day INT;
	DECLARE i_number_of_days INT;
	DECLARE i_expected_number_of_records INT;
	
	
	-- prepare first day of From year From month
	SET v_from_month_first_day = CONCAT(ii_year, '-', ii_month, '-01 00:00:00');
	
	-- prepare first day of next month of To year To month
	SET v_to_month_first_day = DATE_ADD(DATE_ADD(v_from_month_first_day, INTERVAL 1 MONTH), INTERVAL -1 SECOND);	
	
	-- prepare year month
	SET i_year_month = ii_year * 100 + ii_month;
	
	
	-- assume met station performs measurement every 15 minutes
	-- number of records per day = 4 * 24 = 96
	SET i_number_of_records_per_day = 96;

	-- calculate number of days for a particular month
	SET i_number_of_days = DAY(LAST_DAY(v_from_month_first_day));
	
	-- calculate expected number of records = number of records per day * number of days
	SET i_expected_number_of_records = i_number_of_records_per_day * i_number_of_days;


	-- delete monthly_met_data record for a specfic date and a specific station
	DELETE FROM monthly_met_data 
	WHERE year_and_month = i_year_month
	AND station_id = ii_station_id;
	

	-- ***** TO BE CONFIRMED *****
	
	/*
	-- OPTION 1 - generate from met raw data
	
	-- generate monthly_met_data record by summarizing all met raw data records 
	-- for a specific month and a specific station
	INSERT INTO monthly_met_data 
	(year_and_month, station_id, 
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
	DATE_FORMAT(fecha_hora, '%Y%m'), station_id,
	MAX(temperatura_interna), MIN(temperatura_interna), AVG(temperatura_interna),
	MAX(humedad_interna), MIN(humedad_interna), AVG(humedad_interna),
	MAX(temperatura_externa), MIN(temperatura_externa), AVG(temperatura_externa),
	MAX(humedad_externa), MIN(humedad_externa), AVG(humedad_externa),
	MAX(presion_relativa), MIN(presion_relativa), AVG(presion_relativa),
	MAX(presion_absoluta), MIN(presion_absoluta), AVG(presion_absoluta),
	MAX(velocidad_viento), MIN(velocidad_viento), AVG(velocidad_viento),
	MAX(sensacion_termica), MIN(sensacion_termica), AVG(sensacion_termica),
	SUM(lluvia_total), 
	COUNT(*), i_expected_number_of_records,
	NOW(), NOW()
	FROM met_data
	WHERE fecha_hora BETWEEN 
	v_from_month_first_day AND v_to_month_first_day
	AND station_id = ii_station_id
	GROUP BY DATE_FORMAT(fecha_hora, '%Y%m'), station_id;
	*/
	
	-- ========== --
	
	
	-- OPTION 2 - generate from daily met data
	
	-- generate monthly_met_data record by summarizing all daily met data records 
	-- for a specific month and a specific station
	INSERT INTO monthly_met_data 
	(year_and_month, station_id, 
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
	DATE_FORMAT(fecha, '%Y%m'), station_id,
	MAX(max_temperatura_interna), MIN(min_temperatura_interna), AVG(avg_temperatura_interna),
	MAX(max_humedad_interna), MIN(min_humedad_interna), AVG(avg_humedad_interna),
	MAX(max_temperatura_externa), MIN(min_temperatura_externa), AVG(avg_temperatura_externa),
	MAX(max_humedad_externa), MIN(min_humedad_externa), AVG(avg_humedad_externa),
	MAX(max_presion_relativa), MIN(min_presion_relativa), AVG(avg_presion_relativa),
	MAX(max_presion_absoluta), MIN(min_presion_absoluta), AVG(avg_presion_absoluta),
	MAX(max_velocidad_viento), MIN(min_velocidad_viento), AVG(avg_velocidad_viento),
	MAX(max_sensacion_termica), MIN(min_sensacion_termica), AVG(avg_sensacion_termica),
	SUM(lluvia_24_horas_total), 
	SUM(actual_no_of_records), i_expected_number_of_records,
	NOW(), NOW()
	FROM daily_met_data
	WHERE fecha BETWEEN 
	v_from_month_first_day AND v_to_month_first_day
	AND station_id = ii_station_id
	GROUP BY DATE_FORMAT(fecha, '%Y%m'), station_id;	
	
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
        $procedure = "DROP PROCEDURE IF EXISTS `generate_monthly_met_data` ";

        DB::unprepared($procedure);
    }
}
