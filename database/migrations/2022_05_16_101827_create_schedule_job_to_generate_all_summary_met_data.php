<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleJobToGenerateAllSummaryMetData extends Migration
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
CREATE EVENT event_recurring_generate_all_summary_met_data
ON SCHEDULE EVERY 1 DAY
STARTS '2021-11-01 00:00:00'
DO
BEGIN
	CALL generate_all_summary_met_data();
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
        $procedure = "DROP EVENT IF EXISTS event_recurring_generate_all_summary_met_data; ";

        DB::unprepared($procedure);
    }
}
