DROP PROCEDURE IF EXISTS `generate_daily_met_data_by_date_range`;

CREATE PROCEDURE `generate_daily_met_data_by_date_range`(IN id_from_date VARCHAR(10), IN id_to_date VARCHAR(10), IN iv_station_id INT)
BEGIN

/*
	Stored procedure for generating daily_met_data records for a date range for all met stations one by one
*/

	-- day difference between to_date and from_date
	DECLARE diff INT;

	-- loop counter
	DECLARE counter INT;

	-- date variable
	DECLARE d_date DATE;


	-- calculate date difference between to_date and from_date
	SELECT DATEDIFF(id_to_date, id_from_date) INTO diff;

	-- to_date must greater than or equal to from_date
	-- do nothing if to_date is less than from_date
	IF (diff >= 0) THEN

		-- initialize counter
		SET counter = 0;

		-- loop every day
		for_loop: LOOP

			-- exit loop when all days handled
			IF (counter > diff) THEN
				LEAVE for_loop;
			END IF;

			-- calculate a date
			SELECT DATE_ADD(id_from_date, INTERVAL counter DAY) INTO d_date;

			IF (iv_station_id IS NULL) THEN
				-- generate daily summary for all stations for a calculated date
				CALL generate_daily_met_data_by_date(d_date, NULL);
			ELSE
				-- generate daily summary for a station for a calculated date
				CALL generate_daily_met_data_by_date(d_date, iv_station_id);
			END IF;

			-- increment counter
			SET counter = counter + 1;

		END LOOP for_loop;

	END IF;

END
