DROP PROCEDURE IF EXISTS `generate_tendays_met_data_by_part`;

CREATE PROCEDURE `generate_tendays_met_data_by_part`(IN ii_year INT, IN ii_month INT, IN ii_part INT, IN iv_station_id INT)
BEGIN

/*
	Stored procedure for generating tendays_met_data record for a specific month and part for all stations one by one
*/

	-- variable to determine whether it is end of cursor
	DECLARE done INT DEFAULT FALSE;

	-- station ID
	DECLARE station_id INT;

	-- cursor to loop through all station IDs
	DECLARE cur1 CURSOR FOR SELECT id FROM stations ORDER BY id;

	-- handler declaration
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;


	IF (iv_station_id IS NULL) THEN

		-- open cursor
		OPEN cur1;

		-- loop
	  	read_loop: LOOP

	  		-- fetch one record from cursor
			FETCH cur1 INTO station_id;

		    -- exit loop if it is end of cursor
		    IF done THEN
		    	LEAVE read_loop;
		    END IF;

			-- call stored procedure to calculate tendays_met_data for a specific month and part for a specific station
			CALL generate_tendays_met_data(ii_year, ii_month, ii_part, station_id);

	    -- end loop
	  	END LOOP read_loop;

		-- close cursor
		CLOSE cur1;

	ELSE

		-- call stored procedure to calculate tendays_met_data for a specific month and part for a specific station
		CALL generate_tendays_met_data(ii_year, ii_month, ii_part, iv_station_id);

	END IF;

END
