DROP PROCEDURE IF EXISTS `remove_unnecessary_staging_records`;

CREATE PROCEDURE `remove_unnecessary_staging_records`()
BEGIN

    /*
        Stored procedure for removing accumulated unnecessary staging records in table met_data_preview
    */

    -- remove met_data_preview records created 14 days before
    DELETE FROM met_data_preview
    WHERE created_at < DATE_SUB(NOW(), INTERVAL 14 DAY);

END
