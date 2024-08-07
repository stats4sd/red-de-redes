<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYearlyMetDataScheduleJob extends Migration
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
DROP EVENT IF EXISTS event_recurring_generate_yearly_met_data;

-- create a yearly recurring schedule job, with starting date time and ending date time
CREATE EVENT event_recurring_generate_yearly_met_data
ON SCHEDULE EVERY 1 YEAR
STARTS '2022-01-01 03:00:00'
DO
BEGIN
	CALL generate_yearly_met_data_by_year( 
		YEAR( DATE_ADD(CURDATE(), INTERVAL -1 DAY) ) 
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
        $procedure = "DROP EVENT IF EXISTS event_recurring_generate_yearly_met_data;";

        DB::unprepared($procedure);
    }
}
