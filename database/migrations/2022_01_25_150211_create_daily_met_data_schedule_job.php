<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyMetDataScheduleJob extends Migration
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
DROP EVENT IF EXISTS event_recurring_generate_daily_met_data;

-- create a daily recurring schedule job, with starting date time and ending date time
CREATE EVENT event_recurring_generate_daily_met_data
ON SCHEDULE EVERY 1 DAY
STARTS '2022-01-01 01:00:00'
DO
BEGIN
	CALL generate_daily_met_data_by_date( 
		DATE_ADD(CURDATE(), INTERVAL -1 DAY) 
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
        $procedure = "DROP EVENT IF EXISTS event_recurring_generate_daily_met_data;";

        DB::unprepared($procedure);
    }
}
