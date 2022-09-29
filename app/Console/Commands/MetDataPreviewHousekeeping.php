<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class MetDataPreviewHousekeeping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'metdatapreview_housekeeping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform housekeeping for met_data_preview table, remove met_data_preview records older than 14 days';

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
        // remove met_data_preview records older than 14 days
        DB::table('met_data_preview')->where('created_at', '<=', Carbon::now()->subDays(14)->toDateTimeString())->delete();

        return 'success';
    }
}
