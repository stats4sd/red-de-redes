<?php

namespace App\Console\Commands;

use App\Models\Met\Daily;
use App\Models\Met\DailyMetData;
use App\Models\Met\Station;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessMetData extends Command
{

    protected $signature = 'processmetdata';
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // process data up till yesterday (to ensure we have *all* records for the processed days)
        $minDate = DailyMetData::orderByDesc('fecha')->first()->fecha;
        $maxDate = Carbon::now()->subDay()->toDateString();

        foreach (Station::all() as $station) {
            $stationId = $station->id;

            $maxMonth = substr($maxDate, 5, 2);
            $minMonth = substr($minDate, 5, 2);

            $maxYear = substr($maxDate, 0, 4);
            $minYear = substr($minDate, 0, 4);

            \DB::select(
                "call generate_daily_met_data_by_date_range(?, ?, ?);",
                [$minDate, $maxDate, $stationId]
            );

            \DB::select(
                "call generate_tendays_met_data_by_year_range(?, ?, ?);",
                [$minYear, $maxYear, $stationId]
            );

            \DB::select(
                "call generate_monthly_met_data_by_month_range(?, ?, ?, ?, ?);",
                [$minYear, $minMonth, $maxYear, $maxMonth, $stationId]
            );

            \DB::select(
                "call generate_yearly_met_data_by_year_range(?, ?, ?);",
                [$minYear, $maxYear, $stationId]
            );

        }
        return Command::SUCCESS;
    }
}
