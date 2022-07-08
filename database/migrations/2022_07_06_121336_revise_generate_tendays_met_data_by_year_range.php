<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReviseGenerateTendaysMetDataByYearRange extends Migration
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
DROP PROCEDURE IF EXISTS `generate_tendays_met_data_by_year_range`;

CREATE PROCEDURE `generate_tendays_met_data_by_year_range`(IN ii_from_year INT, IN ii_to_year INT, IN iv_station_id INT)
BEGIN

/*
	Stored procedure for generating tendays_met_data records for a year range for all met stations one by one
*/

	-- year difference between To Year and From Year
	DECLARE diff INT;
	
	-- loop counter
	DECLARE counter INT;
	
	SET diff = ii_to_year - ii_from_year;
	
	-- To Year must greater than or equal to From Year
	-- do nothing if To Year is less than From Year
	IF (diff >= 0) THEN
	
		-- initialize counter
		SET counter = 0;
	
		-- loop every year
		for_loop: LOOP
		
			-- exit loop when all years handled
			IF (counter > diff) THEN
				LEAVE for_loop;
			END IF;
			
			IF (iv_station_id IS NULL) THEN
			
				-- generate tendays met data records for all stations for all months of a year
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 1, NULL);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 2, NULL);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 3, NULL);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 4, NULL);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 5, NULL);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 6, NULL);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 7, NULL);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 8, NULL);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 9, NULL);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 10, NULL);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 11, NULL);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 12, NULL);
				
			ELSE

				-- generate tendays met data records for a station for all months of a year
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 1, iv_station_id);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 2, iv_station_id);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 3, iv_station_id);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 4, iv_station_id);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 5, iv_station_id);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 6, iv_station_id);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 7, iv_station_id);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 8, iv_station_id);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 9, iv_station_id);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 10, iv_station_id);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 11, iv_station_id);
				CALL generate_tendays_met_data_by_month(ii_from_year + counter, 12, iv_station_id);
			
			END IF;
			
			-- increment counter
			SET counter = counter + 1;
		
		END LOOP for_loop;
	
	END IF;

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
        $procedure = "DROP PROCEDURE IF EXISTS `generate_tendays_met_data_by_year_range` ";

        DB::unprepared($procedure);
    }
}