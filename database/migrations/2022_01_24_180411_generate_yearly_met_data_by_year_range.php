<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GenerateYearlyMetDataByYearRange extends Migration
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
DROP PROCEDURE IF EXISTS `generate_yearly_met_data_by_year_range`;

CREATE PROCEDURE `generate_yearly_met_data_by_year_range`(IN ii_from_year INT, IN ii_to_year INT)
BEGIN

/*
	Stored procedure for generating yearly_met_data records for a year range for all met stations one by one
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
			
			-- generate yearly met data records for all stations for a year
			CALL generate_yearly_met_data_by_year(ii_from_year + counter);
			
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
        $procedure = "DROP PROCEDURE IF EXISTS `generate_yearly_met_data_by_year_range` ";

        DB::unprepared($procedure);
    }
}
