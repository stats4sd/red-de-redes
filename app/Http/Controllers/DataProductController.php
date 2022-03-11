<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\TendaysExport;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use App\Exports\Download\Met\DailyMetDataExport;
use App\Exports\Download\Met\YearlyMetDataExport;
use App\Exports\Download\Met\MonthlyMetDataExport;
use App\Exports\Download\Met\TendaysMetDataExport;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DataProductController extends Controller
{
    // receive data requests + pass to correct aggregation method
    public function index(Request $request)
    {
        $query = $request->all();
        $aggregation = $query['aggregation'];
        // There are 3 seperate things that might happen here:

        // 1. Generate an Excel file with Laravel Excel + return the resulting file for download
        if ($aggregation === 'daily_data') {
            $filename = "Daily Met Data - " . Carbon::now()->format('Ymd_His') . ".xlsx";
            return Excel::download(new DailyMetDataExport($query), $filename);
        }

        if ($aggregation === 'tendays_data') {
            $filename = "Tendays Met Data - " . Carbon::now()->format('Ymd_His') . ".xlsx";

            return Excel::download(new TendaysMetDataExport($query), $filename);
        }

        if ($aggregation === 'monthly_data') {
            $filename = "Monthly Met Data - " . Carbon::now()->format('Ymd_His') . ".xlsx";

            return Excel::download(new MonthlyMetDataExport($query), $filename);
        }

        if ($aggregation === 'yearly_data') {
            $filename = "Yearly Met Data - " . Carbon::now()->format('Ymd_His') . ".xlsx";

            return Excel::download(new YearlyMetDataExport($query), $filename);
        }


        // 2. Generate an Excel file with R + return the result for download
        if ($aggregation === 'senamhi_daily') {

            // TODO: setup + run Rscript process;

            // senamhi_daily arguments: stations[0], fromYear, meteoIndividualVariable;
            $process = new Process(["Rscript", base_path('scripts/R/senamhi_daily.R'), $query['stations'][0], $query['fromYear'], $query['meteoIndividualVariable']]);

            $process->setWorkingDirectory(base_path('scripts/R'));
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            // return file;
            return file_get_contents(base_path('scripts/R/senamhi_daily.csv'));
        }

        if ($aggregation === 'senamhi_monthly') {

            // TODO: setup + run Rscript process;

            // senamhi_monthly arguments: stations[0], fromYear, toYear meteoIndividualVariable;
            $process = new Process(["Rscript", base_path('scripts/R/senamhi_monthly.R'), $query['stations'][0], $query['fromYear'], $query['toYear'], $query['meteoIndividualVariable']]);

            $process->setWorkingDirectory(base_path('scripts/R'));
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            // return file;
            return file_get_contents(base_path('scripts/R/senamhi_monthly.csv'));
        }


        // 3. Generate an image file with R + return the result for display on the page.

        if ($aggregation === 'heatmap') {

            // If the user has not specified a variable, we should show the inventory graph for "fecha", as that will show when *any* records exist, regardless of the values that are included in each observation.
            $variable = $query['meteoIndividualVariable'] ?? "fecha";

            // senamhi_monthly arguments: stations[0], fromYear, toYear meteoIndividualVariable;
            $process = new Process(["Rscript", base_path('scripts/R/graph_heatmap.R'), $query['stations'][0], $query['fromYear'], $query['toYear'], $variable]);

            $process->setWorkingDirectory(base_path('scripts/R'));
            $process->run();

            if (!$process->isSuccessful()) {
                throw new \Exception($process->getErrorOutput());
            }

            $fileName = "inventario-" . Str::uuid() . ".png";

            // move image into main storage:
            rename(base_path('scripts/R/inventario.png'), storage_path('app/public/' . $fileName));

            // return url to image;
            return Storage::url($fileName);
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
