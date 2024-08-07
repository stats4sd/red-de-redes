DROP PROCEDURE IF EXISTS `generate_all_summary_met_data`;

CREATE PROCEDURE `generate_all_summary_met_data`()
BEGIN

/*
	Stored procedure for generating all summary met data (daily, tendays, monthly, yearly) from all raw met data
*/

	-- remove all existing summary met data records
	DELETE FROM daily_met_data;

	DELETE FROM tendays_met_data;

	DELETE FROM monthly_met_data;

	DELETE FROM yearly_met_data;


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
	CALL generate_daily_met_data_by_date_range(@min_date, @max_date, NULL);

	CALL generate_tendays_met_data_by_year_range(@min_year, @max_year, NULL);

	CALL generate_monthly_met_data_by_month_range(@min_year, @min_month, @max_year, @max_month, NULL);

	CALL generate_yearly_met_data_by_year_range(@min_year, @max_year, NULL);

END
