<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FindMonthPart extends Migration
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

        $function =         
"
CREATE FUNCTION `find_month_part`(
	in_date	VARCHAR(10)

) RETURNS int
    DETERMINISTIC
BEGIN

    /*
        Function for finding the month part of a date
         - Month part: 1 (for day 1 to day 10)
         - Month part: 2 (for day 11 to day 20)
         - Month part: 3 (for day 21 to day 31)
    */

	DECLARE in_day INT;
	
	DECLARE month_part INT;
	
	SET in_day = day(in_date);
	
	IF in_day >= 1 AND in_day <= 10 THEN
		RETURN 1;
		SET month_part = 1;
	ELSEIF in_day >= 11 AND in_day <= 20 THEN
		SET month_part = 2;
	ELSEIF in_day >= 21 AND in_day <= 31 THEN
		SET month_part = 3;
	END IF;
	
	RETURN month_part;

END
";

        DB::unprepared($function);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $function = "DROP FUNCTION IF EXISTS `find_month_part` ";

        DB::unprepared($function);
    }
}
