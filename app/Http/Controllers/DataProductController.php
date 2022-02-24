<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Download\Met\MetDataExport;
use App\Exports\Download\Met\DailyMetDataExport;
use App\Exports\Download\Met\MonthlyMetDataExport;
use App\Exports\Download\Met\TendaysMetDataExport;
use App\Exports\Download\Met\YearlyMetDataExport;
use App\Exports\TendaysExport;
use Illuminate\Support\Facades\Storage;

class DataProductController extends Controller
{
    // receive data requests + pass to correct aggregation method
    public function index(Request $request)
    {
        $query = $request->all();
        $aggregation = $query['aggregation'];
        // There are 3 seperate things that might happen here:

        // 1. Generate an Excel file with Laravel Excel + return the resulting file path for download
        if ($aggregation === 'raw_data') {
            $filename = "Raw Met Data - ".Carbon::now()->format('Ymd_His').".xlsx";
            return Excel::download(new MetDataExport($query), $filename);
        }

        if ($aggregation === 'daily_data') {
            $filename = "Daily Met Data - ".Carbon::now()->format('Ymd_His').".xlsx";
            return Excel::download(new DailyMetDataExport($query), $filename);
        }

        if ($aggregation === 'tendays_data') {
            $filename = "Tendays Met Data - ".Carbon::now()->format('Ymd_His').".xlsx";

            return Excel::download(new TendaysMetDataExport($query), $filename);
        }

        if ($aggregation === 'monthly_data') {
            $filename = "Monthly Met Data - ".Carbon::now()->format('Ymd_His').".xlsx";

            return Excel::download(new MonthlyMetDataExport($query), $filename);
        }

        if ($aggregation === 'yearly_data') {
            $filename = "Yearly Met Data - ".Carbon::now()->format('Ymd_His').".xlsx";

            return Excel::download(new YearlyMetDataExport($query), $filename);
        }


        // 2. Generate an Excel file with R + return the result for download
        if ($aggregation === 'senamhi_daily') {

            // TODO: setup + run Rscript process;

            // get filename of Rscript output (+ move it to correct storage path if needed)

            // return file;
        }

        if ($aggregation === 'senamhi_monthly') {

            // TODO: setup + run Rscript process;

            // get filename of Rscript output (+ move it to correct storage path if needed)

            // return file;
        }


        // 3. Generate an image file with R + return the result for display on the page.

        if ($aggregation === 'heatmap') {
            // TODO: setup + run Rscript process;

            // get filename of Rscript outout (+ move it to correct storage path if needed)

            // return url to file for Vue to present;
        }

        if ($aggregation === 'time_series') {
            // TODO: setup + run Rscript process;

            // get filename of Rscript outout (+ move it to correct storage path if needed)

            // return url to file for Vue to present;
        }

        if ($aggregation === 'boxplot') {
            // TODO: setup + run Rscript process;

            // get filename of Rscript outout (+ move it to correct storage path if needed)

            // return url to file for Vue to present;
        }

        return null;
    }
}
