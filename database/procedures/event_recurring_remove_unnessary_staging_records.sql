DROP EVENT IF EXISTS event_recurring_remove_unnecessary_staging_records;

CREATE EVENT event_recurring_remove_unnecessary_staging_records
ON SCHEDULE EVERY 1 DAY
STARTS '2021-11-01 00:00:00'
DO
BEGIN
	CALL remove_unnecessary_staging_records();
END;
