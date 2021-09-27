<?php

use Illuminate\Database\Migrations\Migration;

class AddTriggerToData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("

            CREATE TRIGGER get_station_id  BEFORE INSERT ON data
                FOR EACH ROW
                BEGIN
                IF NEW.station_id IS NULL THEN
                    SET NEW.station_id = (
                        SELECT id FROM stations WHERE hardware_id = NEW.hardware_id  AND
                        longitude = NEW.meteobridge_longitude AND
                        latitude = NEW.meteobridge_latitude
                       );
                END IF ;

                END

            ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       DB::unprepared('DROP TRIGGER get_station_id');
    }
}
