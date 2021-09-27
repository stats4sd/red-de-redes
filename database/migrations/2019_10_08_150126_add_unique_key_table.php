<?php

use Illuminate\Database\Migrations\Migration;

class AddUniqueKeyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            ALTER TABLE `data`
            DROP PRIMARY KEY,
            ADD COLUMN `id` BIGINT NOT NULL AUTO_INCREMENT AFTER `fecha_hora`,
            ADD PRIMARY KEY (`id`),
            ADD UNIQUE INDEX `fecha_hora` (`fecha_hora`, `station_id`);

        ");
        DB::statement("
            ALTER TABLE `data_template`
            DROP PRIMARY KEY,
            ADD COLUMN `id` BIGINT NOT NULL AUTO_INCREMENT AFTER `fecha_hora`,
            ADD PRIMARY KEY (`id`),
            ADD UNIQUE INDEX `fecha_hora` (`fecha_hora`, `station_id`);

        ");
        DB::statement("
            ALTER TABLE `meteobridge`
            DROP PRIMARY KEY,
            ADD COLUMN `id` BIGINT NOT NULL AUTO_INCREMENT AFTER `fecha_hora`,
            ADD PRIMARY KEY (`id`),
            ADD UNIQUE INDEX `fecha_hora` (`fecha_hora`, `station_id`);

        ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
