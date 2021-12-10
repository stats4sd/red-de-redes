<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUnnecessaryStagingRecords extends Migration
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
DROP PROCEDURE IF EXISTS `remove_unnecessary_staging_records`;

CREATE DEFINER=`root`@`localhost` PROCEDURE `remove_unnecessary_staging_records`()
BEGIN
        
    /*
        Stored procedure for removing accumulated unnecessary staging records in table data_template
    */
        
    -- remove data_template records created 3 days before
    DELETE FROM data_template 
    WHERE created_at < DATE_SUB(NOW(), INTERVAL 3 DAY);
        
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
        $procedure = "DROP PROCEDURE IF EXISTS `remove_unnecessary_staging_records` ";

        DB::unprepared($procedure);
    }
}
