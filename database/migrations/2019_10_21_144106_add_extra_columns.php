<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class AddExtraColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data', function($table){
            $table->decimal('leaf_temp_1')->nullable();
            $table->decimal('leaf_temp_2')->nullable();
            $table->decimal('soil_temp_1')->nullable();
            $table->decimal('soil_temp_2')->nullable();
        });

        Schema::table('meteobridge', function($table){
            $table->decimal('leaf_temp_1')->nullable();
            $table->decimal('leaf_temp_2')->nullable();
            $table->decimal('soil_temp_1')->nullable();
            $table->decimal('soil_temp_2')->nullable();
        });

        Schema::table('data_template', function($table){
            $table->decimal('leaf_temp_1')->nullable();
            $table->decimal('leaf_temp_2')->nullable();
            $table->decimal('soil_temp_1')->nullable();
            $table->decimal('soil_temp_2')->nullable();
        });
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
