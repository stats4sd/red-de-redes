<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GenerateDeltaSummaryMetData extends Migration
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
DROP PROCEDURE IF EXISTS `generate_delta_summary_met_data`;

CREATE PROCEDURE `generate_delta_summary_met_data`()
BEGIN

/*
	Stored procedure for generating delta summary met data (daily, tendays, monthly, yearly) from newly uploaded raw met data
	P.S. use multiple BEGIN END blocks to separate different part of business logic
	
	Program flow:
	1. Generate working table records to find out which date has newly uploaded raw met data
	2. Re-generate delta daily_met_data
	3. Re-generate delta tendays_met_data
	4. Re-generate delta monthly_met_data
	5. Re-generate delta yearly_met_data
	6. Print debug message	
*/

	-- 1. Generate working table records to find out which date has newly uploaded raw met data
	BEGIN
		-- remove all existing records in working table
		DELETE FROM wk_summary_met_data;
	
		-- Below are all possible cases:
		-- 1. More number of records: There were existing raw met data before, and there are some new raw met data now. Re-generate summary met data records
		-- 2. From non existed to existed: There was no raw met data before, and there are some new met data now. Re-generate summary met data records
		-- 3. Less number of records: Suppose it should not happen as there is no mechanism to remove the uploaded raw met data in database. Re-generate summary met data records
	    -- 4. From existed to non existed: Suppose it should not happen, there were raw met data before, and there is no met data now. This case is not detected	    
	    -- 5. Equial number of records: That means no new raw met data uploaded. No action is required
	    --
	    -- Conclusion:
	    -- 1. Suppose we should re-generate summary met data records for dates with difference > 0 only (new records found)
	    -- 2. To play safe, also re-generate summary met data records for date with difference < 0 (some records removed),
	    --    although it should not happen. Just in case raw met data records removed due to some reasons
	    -- 3. There will be no record in working table if all raw met data removed for a particular date and station.
	    --    In this case, the previously generated daily_met_data records will still be used for generating tendays, monthly, yearly met data.
	    --    Note that this case is not detected, and this case should not happen.
	    -- 4. No need to do anything for records with difference = 0 (no change on raw met data records)
	    --
		
		INSERT INTO wk_summary_met_data (fecha, station_id, year, month, part, latest_actual_no_of_records, previous_actual_no_of_records, difference, previous_created_at, created_at, updated_at)
		SELECT tb.fecha, tb.station_id, YEAR(tb.fecha) AS year, MONTH(tb.fecha) AS month, find_month_part(tb.fecha) AS part, 
		COUNT(*) AS latest_actual_no_of_records, tb.actual_no_of_records AS previous_actual_no_of_records, 
		COUNT(*) - IFNULL(tb.actual_no_of_records, 0) AS difference,
		tb.created_at AS previous_created_at, NOW(), NOW()
		FROM met_data ta 
		LEFT JOIN daily_met_data tb 
		ON DATE(ta.fecha_hora) = tb.fecha
		AND ta.station_id = tb.station_id
		GROUP BY tb.fecha, tb.station_id;

	END;



	-- 2. Re-generate delta daily_met_data
	BEGIN
		-- variable to determine whether it is end of cursor
		DECLARE done INT DEFAULT FALSE;
		
		-- variables for retrieving values from cursors
		-- P.S. local variable names and table column name must not be the same
		DECLARE v_fecha VARCHAR(10);
		DECLARE i_station_id INT;

		-- cursor to loop through all fecha and station with difference
		DECLARE cur CURSOR FOR SELECT fecha, station_id FROM wk_summary_met_data WHERE difference <> 0;
		
		-- handler declaration
		DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	
		-- open cursor
		OPEN cur;
		
		-- loop
	  	read_loop: LOOP
	  	
	  		-- fetch one record from cursor
			FETCH cur INTO v_fecha, i_station_id;
			
		    -- exit loop if it is end of cursor
		    IF done THEN
		    	LEAVE read_loop;
		    END IF;
		    
		    -- call stored procedure to calculate daily_met_data for a specific date for a specific station
		    CALL generate_daily_met_data(v_fecha, i_station_id);
	    
	    -- end loop
	  	END LOOP read_loop;
		
		-- close cursor
		CLOSE cur;
	END;
	
	
	
	-- 3. Re-generate delta tendays_met_data
	BEGIN
		-- variable to determine whether it is end of cursor
		DECLARE done INT DEFAULT FALSE;
		
		-- variables for retrieving values from cursors
		-- P.S. local variable names and table column name must not be the same
		DECLARE i_year INT;
		DECLARE i_month INT;
		DECLARE i_part INT;
		DECLARE i_station_id INT;

		-- cursor to loop through all year, month, part and station with difference
		DECLARE cur CURSOR FOR SELECT year, month, part, station_id FROM wk_summary_met_data WHERE difference <> 0 GROUP BY year, month, part, station_id;
		
		-- handler declaration
		DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	
		-- open cursor
		OPEN cur;
		
		-- loop
	  	read_loop: LOOP
	  	
	  		-- fetch one record from cursor
			FETCH cur INTO i_year, i_month, i_part, i_station_id;
			
		    -- exit loop if it is end of cursor
		    IF done THEN
		    	LEAVE read_loop;
		    END IF;
		    
		    -- call stored procedure to calculate tendays_met_data for a specific year, month, part for a specific station
		    CALL generate_tendays_met_data(i_year, i_month, i_part, i_station_id);
	    
	    -- end loop
	  	END LOOP read_loop;
		
		-- close cursor
		CLOSE cur;
	END;
	


	-- 4. Re-generate delta monthly_met_data
	BEGIN
		-- variable to determine whether it is end of cursor
		DECLARE done INT DEFAULT FALSE;
		
		-- variables for retrieving values from cursors
		-- P.S. local variable names and table column name must not be the same
		DECLARE i_year INT;
		DECLARE i_month INT;
		DECLARE i_station_id INT;
	
		-- cursor to loop through all year, month and station with difference
		DECLARE cur CURSOR FOR SELECT year, month, station_id FROM wk_summary_met_data WHERE difference <> 0 GROUP BY year, month, station_id;
		
		-- handler declaration
		DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	
		-- open cursor
		OPEN cur;
		
		-- loop
	  	read_loop: LOOP
	  	
	  		-- fetch one record from cursor
			FETCH cur INTO i_year, i_month, i_station_id;
			
		    -- exit loop if it is end of cursor
		    IF done THEN
		    	LEAVE read_loop;
		    END IF;
		    
		    -- call stored procedure to calculate monthly_met_data for a specific year, month for a specific station
		    CALL generate_monthly_met_data(i_year, i_month, i_station_id);
	    
	    -- end loop
	  	END LOOP read_loop;
		
		-- close cursor
		CLOSE cur;
	END;



	-- 5. Re-generate delta yearly_met_data
	BEGIN
		-- variable to determine whether it is end of cursor
		DECLARE done INT DEFAULT FALSE;
		
		-- variables for retrieving values from cursors
		-- P.S. local variable names and table column name must not be the same
		DECLARE i_year INT;
		DECLARE i_station_id INT;
	
		-- cursor to loop through all year and station with difference
		DECLARE cur CURSOR FOR SELECT year, station_id FROM wk_summary_met_data WHERE difference <> 0 GROUP BY year, station_id;
		
		-- handler declaration
		DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	
		-- open cursor
		OPEN cur;
		
		-- loop
	  	read_loop: LOOP
	  	
	  		-- fetch one record from cursor
			FETCH cur INTO i_year, i_station_id;
			
		    -- exit loop if it is end of cursor
		    IF done THEN
		    	LEAVE read_loop;
		    END IF;
		    
		    -- call stored procedure to calculate yearly_met_data for a specific year for a specific station
		    CALL generate_yearly_met_data(i_year, i_station_id);
	    
	    -- end loop
	  	END LOOP read_loop;
		
		-- close cursor
		CLOSE cur;
	END;
	

	-- 6. Print debug message
	SELECT 'Completed' from dual;
	
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
        $procedure = "DROP PROCEDURE IF EXISTS `generate_delta_summary_met_data` ";

        DB::unprepared($procedure);
    }
}
