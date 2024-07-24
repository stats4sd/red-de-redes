DROP EVENT IF EXISTS event_recurring_generate_monthly_met_data;

-- create a monthly recurring schedule job, with starting date time and ending date time
CREATE EVENT event_recurring_generate_monthly_met_data
ON SCHEDULE EVERY 1 MONTH
STARTS '2022-01-01 02:00:00'
DO
BEGIN
	CALL generate_monthly_met_data_by_month(
		YEAR( DATE_ADD(CURDATE(), INTERVAL -1 DAY) ),
		MONTH( DATE_ADD(CURDATE(), INTERVAL -1 DAY) )
	);
END;
