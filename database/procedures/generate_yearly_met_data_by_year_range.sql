DROP PROCEDURE IF EXISTS `generate_yearly_met_data_by_year_range`;

CREATE PROCEDURE `generate_yearly_met_data_by_year_range`(IN ii_from_year INT, IN ii_to_year INT, IN iv_station_id INT)
BEGIN

/*
	Stored procedure for generating yearly_met_data records for a year range for all met stations one by one
*/

	-- year difference between To Year and From Year
	DECLARE diff INT;

	-- loop counter
	DECLARE counter INT;

	SET diff = ii_to_year - ii_from_year;

	-- To Year must greater than or equal to From Year
	-- do nothing if To Year is less than From Year
	IF (diff >= 0) THEN

		-- initialize counter
		SET counter = 0;

		-- loop every year
		for_loop: LOOP

			-- exit loop when all years handled
			IF (counter > diff) THEN
				LEAVE for_loop;
			END IF;

			IF (iv_station_id IS NULL) THEN
				-- generate yearly met data records for all stations for a year
				CALL generate_yearly_met_data_by_year(ii_from_year + counter, NULL);
			ELSE
				-- generate yearly met data records for a station for a year
				CALL generate_yearly_met_data_by_year(ii_from_year + counter, iv_station_id);
			END IF;

			-- increment counter
			SET counter = counter + 1;

		END LOOP for_loop;

	END IF;

END
