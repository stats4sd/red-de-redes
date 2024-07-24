DROP EVENT IF EXISTS event_recurring_generate_tensday_met_data_part_one;

-- create a monthly recurring schedule job, with starting date time and ending date time
CREATE EVENT event_recurring_generate_tensday_met_data_part_one
ON SCHEDULE EVERY 1 MONTH
STARTS '2022-01-11 04:00:00'
DO
BEGIN
	CALL generate_tendays_met_data_by_part(
		YEAR( CURDATE() ),
		MONTH( CURDATE() ),
        1
	);
END;
