<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleJobForRemoveUnnecessaryStagingRecords extends Migration
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
CREATE EVENT event_recurring_remove_unnecessary_staging_records
ON SCHEDULE EVERY 1 DAY
STARTS '2021-11-01 00:00:00'
DO
BEGIN
	CALL remove_unnecessary_staging_records();
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
        $procedure = "DROP EVENT IF EXISTS event_recurring_remove_unnecessary_staging_records; ";

        DB::unprepared($procedure);
    }
}
