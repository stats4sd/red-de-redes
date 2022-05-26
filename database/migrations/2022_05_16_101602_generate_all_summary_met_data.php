<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GenerateAllSummaryMetData extends Migration
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
DROP PROCEDURE IF EXISTS `generate_all_summary_met_data`;

CREATE PROCEDURE `generate_all_summary_met_data`()
BEGIN

/*
	Stored procedure for generating all summary met data (daily, tendays, monthly, yearly) from all raw met data
*/

	-- find minimum and maximum date, year, month from raw met data
	SELECT
		DATE(MIN(fecha_hora)), YEAR(MIN(fecha_hora)), MONTH(MIN(fecha_hora)),
		DATE(MAX(fecha_hora)), YEAR(MAX(fecha_hora)), MONTH(MAX(fecha_hora))
	INTO
		@min_date, @min_year, @min_month,
		@max_date, @max_year, @max_month
	FROM 
		met_data;

	-- call stored procedures to generate all summary met data (daily, tendays, monthly, yearly)
	CALL generate_daily_met_data_by_date_range(@min_date, @max_date);
	
	CALL generate_tendays_met_data_by_year_range(@min_year, @max_year);
	
	CALL generate_monthly_met_data_by_month_range(@min_year, @min_month, @max_year, @max_month);
	
	CALL generate_yearly_met_data_by_year_range(@min_year, @max_year);

	-- debug message
	-- SELECT @min_date, @min_year, @min_month, @max_date, @max_year, @max_month;
	
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
        $procedure = "DROP PROCEDURE IF EXISTS `generate_all_summary_met_data` ";

        DB::unprepared($procedure);
    }
}
