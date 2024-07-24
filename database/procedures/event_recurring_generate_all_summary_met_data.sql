DROP EVENT IF EXISTS event_recurring_generate_all_summary_met_data;

CREATE EVENT IF NOT EXISTS event_recurring_generate_all_summary_met_data
ON SCHEDULE EVERY 1 DAY
STARTS '2021-11-01 00:00:00'
DO
BEGIN
	CALL generate_all_summary_met_data();
END;
