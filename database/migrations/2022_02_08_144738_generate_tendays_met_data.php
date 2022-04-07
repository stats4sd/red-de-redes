<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GenerateTendaysMetData extends Migration
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
DROP PROCEDURE IF EXISTS `generate_tendays_met_data`;

CREATE PROCEDURE `generate_tendays_met_data`(IN ii_year INT, IN ii_month INT, IN ii_part INT, IN ii_station_id INT)
BEGIN

/*
	Stored procedure for generating tendays_met_data record for a specific year, month, part and a specific station
*/
	
	/*
		Part 1: Day 1 to Day 10
		Part 2: Day 11 to Day 20
		Part 3: Day 21 to Day 28/29/30/31 (calculate dynamically)	
	*/
	DECLARE v_part_first_day VARCHAR(19);
	DECLARE v_part_last_day VARCHAR(19);
	DECLARE n_days_in_month INT;
	
	DECLARE i_year_month INT;
	
	DECLARE i_number_of_records_per_day INT;
	DECLARE i_number_of_days INT;
	DECLARE i_expected_number_of_records INT;
	

	-- prepare first day and last day
	if (ii_part = 1) THEN
		SET v_part_first_day = CONCAT(ii_year, '-', ii_month, '-01 00:00:00');
		SET v_part_last_day = CONCAT(ii_year, '-', ii_month, '-10 23:59:59');		
		
	ELSEIF (ii_part = 2) THEN
		SET v_part_first_day = CONCAT(ii_year, '-', ii_month, '-11 00:00:00');
		SET v_part_last_day = CONCAT(ii_year, '-', ii_month, '-20 23:59:59');		
	
	ELSEIF (ii_part = 3) THEN
		SET v_part_first_day = CONCAT(ii_year, '-', ii_month, '-21 00:00:00');
		SET n_days_in_month = DAY(LAST_DAY(CONCAT(ii_year, '-', ii_month, '-01')));
		SET v_part_last_day = CONCAT(ii_year, '-', ii_month, '-', n_days_in_month, ' 23:59:59');
		
	END IF;
	
	
	-- prepare year month
	SET i_year_month = ii_year * 100 + ii_month;
	
	-- assume met station performs measurement every 15 minutes
	-- number of records per day = 4 * 24 = 96
	SET i_number_of_records_per_day = 96;

	-- calculate number of days for a particular month
	IF (ii_part = 1) THEN
		SET i_number_of_days = 10;
		
	ELSEIF (ii_part = 2) THEN
		SET i_number_of_days = 10;
	
	ELSEIF (ii_part = 3) THEN
		SET i_number_of_days = n_days_in_month - 20;
	
	END IF;
	
	-- calculate expected number of records = number of records per day * number of days
	SET i_expected_number_of_records = i_number_of_records_per_day * i_number_of_days;


	-- delete monthly_met_data record for a specfic date and a specific station
	DELETE FROM tendays_met_data 
	WHERE year_and_month = i_year_month
	AND part = ii_part
	AND station_id = ii_station_id;
	
	
	-- ***** TO BE CONFIRMED *****
	
	
	-- OPTION 1 - generate from met raw data
	
	-- generate tendays_met_data record by summarizing all met raw data records 
	-- for a specific month, part and a specific station
	/*
	INSERT INTO tendays_met_data 
	(year_and_month, part, station_id, 
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
	DATE_FORMAT(fecha_hora, '%Y%m'), ii_part, station_id,
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
	v_part_first_day AND v_part_last_day
	AND station_id = ii_station_id
	GROUP BY DATE_FORMAT(fecha_hora, '%Y%m'), station_id;
	*/
		
	-- ========== --
	
	
	-- OPTION 2 - generate from daily met data
	
	-- generate tendays_met_data record by summarizing all daily met data records 
	-- for a specific month, part and a specific station
	INSERT INTO tendays_met_data 
	(year_and_month, part, station_id, 
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
	DATE_FORMAT(fecha, '%Y%m'), ii_part, station_id,
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
	v_part_first_day AND v_part_last_day
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
        $procedure = "DROP PROCEDURE IF EXISTS `generate_tendays_met_data` ";

        DB::unprepared($procedure);
    }
}
