<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use App\Exports\Download\Met\MetDataWorkbookExport;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DataProductController extends Controller
{
    // receive data requests + pass to correct aggregation method
    public function index(Request $request)
    {
        // get all parameters from the posted HTTP form
        $query = $request->all();

        // which action to be taken
        // possible values: show_graph, download_file
        $actionType = $query['actionType'];

        // which aggregated met data to be generated
        // possible values: daily_data, tendays_data, monthly_data, yearly_data, senamhi_daily, senamhi_monthly

        // do existence check because aggregation is available for data download only
        if (array_key_exists('aggregation', $query)) {
            $aggregation = $query['aggregation'];
        }
        
        // which graph to be generated
        // possible values: heatmap, time_series, boxplot
        $graphType = $query['graphType'];


        // There are 3 seperate things that might happen here:

        if ($actionType === 'download_file') {

            // 1. Generate an Excel file with Laravel Excel + return the resulting file for download

            if ($aggregation === 'daily_data' || $aggregation === 'tendays_data' || $aggregation === 'monthly_data' || $aggregation === 'yearly_data') {
                // filename will be refined when download file in Vue component level
                $filename = "Datos meteorologicos - " . Carbon::now()->format('Ymd_His') . ".xlsx";
                return Excel::download(new MetDataWorkbookExport($query), $filename);
            }

            // 2. Generate an CSV file with R + return the result for download
            if ($aggregation === 'senamhi_daily') {

                // setup + run Rscript process;

                // senamhi_daily arguments: stations[0], fromYear, meteoIndividualVariable;
                $process = new Process(["Rscript", base_path('scripts/R/senamhi_daily.R'), $query['stations'][0], $query['fromYear'], $query['meteoIndividualVariable']]);

                $process->setWorkingDirectory(base_path('scripts/R'));
                $process->run();

                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }

                // return file;
                // question: it maybe a problem when two or more users are generating senamhi daily, user A may get result of user B
                return file_get_contents(base_path('scripts/R/senamhi_daily.xlsx'));
            }

            if ($aggregation === 'senamhi_monthly') {

                // setup + run Rscript process;

                // senamhi_monthly arguments: stations[0], fromYear, toYear meteoIndividualVariable;
                $process = new Process(["Rscript", base_path('scripts/R/senamhi_monthly.R'), $query['stations'][0], $query['fromYear'], $query['toYear'], $query['meteoIndividualVariable']]);

                $process->setWorkingDirectory(base_path('scripts/R'));
                $process->run();

                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }

                // return file;
                // question: it maybe a problem when two or more users are generating senamhi daily, user A may get result of user B
                return file_get_contents(base_path('scripts/R/senamhi_monthly.xlsx'));
            }
        }


        if ($actionType === 'show_graph') {

            // 3. Generate an image file with R + return the result for display on the page.

            if ($graphType === 'heatmap') {

                // If the user has not specified a variable, we should show the inventory graph for "fecha", as that will show when *any* records exist, regardless of the values that are included in each observation.
                //$variable = $query['meteoIndividualVariable'] ?? "fecha";

                // variable set to "fecha" by default for heatmap graph generation
                // use user selected value only for semamhi daily and senamhi monthly
                $variable = "fecha";

                if ($query['aggregation'] === 'senamhi_daily' || $query['aggregation'] === 'senamhi_monthly') {
                    $variable = $query['meteoIndividualVariable'];
                }

                // senamhi_monthly arguments: stations[0], fromYear, toYear, meteoIndividualVariable;
                $process = new Process(["Rscript", base_path('scripts/R/graph_heatmap.R'), $query['aggregation'], join(",", $query['stations']), $query['fromYear'], $query['toYear'], $variable]);

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

            if ($graphType === 'time_series') {

                // time series graph arguments: stations[0], fromYear, toYear, meteoVariableType;
                $process = new Process(["Rscript", base_path('scripts/R/graph_timeseries.R'), $query['station'], $query['fromYear'], $query['toYear'], $query['meteoVariableType']]);

                $process->setWorkingDirectory(base_path('scripts/R'));
                $process->run();

                if (!$process->isSuccessful()) {
                    throw new \Exception($process->getErrorOutput());
                }

                $fileName = "grafico_series_temporales-" . Str::uuid() . ".png";

                // move image into main storage:
                rename(base_path('scripts/R/grafico_series_temporales.png'), storage_path('app/public/' . $fileName));

                // return url to image;
                return Storage::url($fileName);

            }

            if ($graphType === 'boxplot') {

                // boxplot graph arguments: stations[0], fromYear, toYear, meteoVariableType;
                $process = new Process(["Rscript", base_path('scripts/R/graph_boxplot.R'), $query['station'], $query['fromYear'], $query['toYear'], $query['meteoVariableType']]);

                $process->setWorkingDirectory(base_path('scripts/R'));
                $process->run();

                if (!$process->isSuccessful()) {
                    throw new \Exception($process->getErrorOutput());
                }

                $fileName = "grafico_boxplot-" . Str::uuid() . ".png";

                // move image into main storage:
                rename(base_path('scripts/R/grafico_boxplot.png'), storage_path('app/public/' . $fileName));

                // return url to image;
                return Storage::url($fileName);

            }
        }

        return null;
    }
}
