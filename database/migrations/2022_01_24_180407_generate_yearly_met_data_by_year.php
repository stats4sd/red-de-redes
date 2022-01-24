<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GenerateYearlyMetDataByYear extends Migration
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
DROP PROCEDURE IF EXISTS `generate_yearly_met_data_by_year`;

CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_yearly_met_data_by_year`(IN ii_year INT)
BEGIN

/*
	Stored procedure for generating yearly_met_data record for a specific year for all stations one by one
*/

	-- variable to determine whether it is end of cursor
	DECLARE done INT DEFAULT FALSE;
	
	-- station ID
	DECLARE station_id INT;
	
	-- cursor to loop through all station IDs
	DECLARE cur1 CURSOR FOR SELECT id FROM stations ORDER BY id;
	
	-- handler declaration
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;


	-- open cursor
	OPEN cur1;
	
	-- loop
  	read_loop: LOOP
  	
  		-- fetch one record from cursor
		FETCH cur1 INTO station_id;
	    
	    -- exit loop if it is end of cursor
	    IF done THEN
	    	LEAVE read_loop;
	    END IF;
	    
	    -- call stored procedure to calculate yearly_met_data for a specific year for a specific station
	    CALL generate_yearly_met_data(ii_year, station_id);
    
    -- end loop
  	END LOOP read_loop;
	
	-- close cursor
	CLOSE cur1;
	
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
        $procedure = "DROP PROCEDURE IF EXISTS `generate_yearly_met_data_by_year` ";

        DB::unprepared($procedure);
    }
}
