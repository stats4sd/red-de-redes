<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReviseGenerateMonthlyMetDataByMonthRange extends Migration
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
DROP PROCEDURE IF EXISTS `generate_monthly_met_data_by_month_range`;

CREATE PROCEDURE `generate_monthly_met_data_by_month_range`(IN ii_from_year INT, IN ii_from_month INT, IN ii_to_year INT, IN ii_to_month INT, IN iv_station_id INT)
BEGIN

/*
	Stored procedure for generating monthly_met_data records for a month range for all met stations one by one
*/

	-- day difference between to_date and from_date
	DECLARE diff INT;
	
	-- loop counter
	DECLARE counter INT;
	
	-- date variable
	DECLARE d_date DATE;
	
	-- year, month variable
	DECLARE i_year INT;
	DECLARE i_month INT;	
	
	-- first day of From year From month
	DECLARE v_from_month_first_day DATE;
	
	-- prepare first day of From year From month
	SET v_from_month_first_day = CONCAT(ii_from_year, '-', ii_from_month, '-01');

	-- calculate month difference between To Year To Month and From Year From Month
	SELECT PERIOD_DIFF( (ii_to_year * 100 + ii_to_month), (ii_from_year * 100 + ii_from_month) ) INTO diff;
	
	-- To Year and To Month must greater than or equal to From Year From Month
	-- do nothing if To Year To Month is less than From Year From Month
	IF (diff >= 0) THEN
	
		-- initialize counter
		SET counter = 0;
	
		-- loop every month
		for_loop: LOOP
		
			-- exit loop when all months handled
			IF (counter > diff) THEN
				LEAVE for_loop;
			END IF;
			
			-- calculate a date for first day of a month
			SELECT DATE_ADD(v_from_month_first_day, INTERVAL counter MONTH) INTO d_date;
			
			-- get year and month from the calculated date
			SET i_year = DATE_FORMAT(d_date, '%Y');
			SET i_month = DATE_FORMAT(d_date, '%m');

			IF (iv_station_id IS NULL) THEN
				-- generate monthly summary for all stations for a calculated date
				CALL generate_monthly_met_data_by_month(i_year, i_month, NULL);
			ELSE
				-- generate monthly summary for a station for a calculated date
				CALL generate_monthly_met_data_by_month(i_year, i_month, iv_station_id);			
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
        $procedure = "DROP PROCEDURE IF EXISTS `generate_monthly_met_data_by_month_range` ";

        DB::unprepared($procedure);
    }
}