<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWkSummaryMetDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wk_summary_met_data', function (Blueprint $table) {
            $table->id();

            // date and station id, requried fields
            $table->date('fecha');
            $table->integer('station_id')->default('0');

            $table->integer('year');
            $table->integer('month');
            $table->integer('part');

            $table->integer('latest_actual_no_of_records');
            $table->integer('previous_actual_no_of_records')->nullable();
            $table->integer('difference');

            $table->timestamp('previous_created_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wk_summary_met_data');
    }
}
