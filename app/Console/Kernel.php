<?php

namespace App\Console;

use App\Models\Xlsform;
use App\Jobs\MediaFiles\UpdateFormCsvFiles;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        foreach (Xlsform::all() as $xlsform) {
            $schedule->job(new UpdateFormCsvFiles($xlsform))
            ->daily();
        }

        // daily schedule job to remove met_data_preview records older than 14 days
        $schedule->command('metdatapreview_housekeeping')->dailyAt('00:01');
        $schedule->command('processmetdata')->dailyAt('06:15');

        // purge telescope entries older than 1 week
        $schedule->command('app:purge-telescope-entries')->weeklyOn(dayOfWeek: Schedule::TUESDAY);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
