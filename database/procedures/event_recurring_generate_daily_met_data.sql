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
