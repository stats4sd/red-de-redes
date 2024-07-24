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
