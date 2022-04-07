<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTendaysMetDataPartTwoScheduleJob extends Migration
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
DROP EVENT IF EXISTS event_recurring_generate_tensday_met_data_part_two;

-- create a monthly recurring schedule job, with starting date time and ending date time
CREATE EVENT event_recurring_generate_tensday_met_data_part_two
ON SCHEDULE EVERY 1 MONTH
STARTS '2022-01-21 04:00:00'
DO
BEGIN
	CALL generate_tendays_met_data_by_part( 
		YEAR( CURDATE() ), 
		MONTH( CURDATE() ),
        2
	);
END;
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
        $procedure = "DROP EVENT IF EXISTS event_recurring_generate_tensday_met_data_part_two;";

        DB::unprepared($procedure);
    }
}
