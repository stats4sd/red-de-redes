<?php

namespace App\Http\Controllers;

use App\Events\MetDataImportCompleted;
use App\Events\MetDataImportFailed;
use App\Events\MetDataImportStarted;
use App\Http\Requests\FileRequest;
use App\Imports\DavisFileHeaderValidation;
use App\Imports\DavisFileImport;
use App\Imports\PreProcessDavisHeaders;
use App\Jobs\MetDataImportCompletedJob;
use App\Jobs\StartMetDataImport;
use App\Models\Met\File;
use App\Models\Met\MetData;
use App\Models\Met\Station;
use App\Models\Met\Daily;
use Exception;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Met\MetDataPreview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use JsonException;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Prologue\Alerts\Facades\Alert;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Redirect;

class FileController extends Controller
{

    /**
     * @throws JsonException
     */
    public function store(FileRequest $request): JsonResponse
    {
        // create File Record
        $validated = $request->validated();

        // get observation file details
        $observationFile = $validated['observationFile'] ?? null;
        $observationFileName = isset($validated['observationFile']) ? $validated['observationFile']->getClientOriginalName() : null;

        $fileRecord = File::create([
            'data_file' => $validated['data_file'],
            'name' => $validated['data_file']->getClientOriginalName(),
            // original name is now (potentially) legacy?
            'original_name' => $validated['data_file']->getClientOriginalName(),
            'observation_file' => $observationFile,
            'observation_file_name' => $observationFileName,
            'station_id' => $validated['station_id'],
            'uploader_id' => Auth::id(),
            'upload_id' => $this->generateRandomString(),
        ]);

        // upload data to met_data_preview table
        if (Station::find($fileRecord['station_id'])->type === 'davis') {
            $processor = (new PreProcessDavisHeaders());
            [$fileWithMergedHeaders, $headerValidationFile, $count] = $processor($fileRecord->data_file);

            $fileRecord->update(['total_records_count' => $count]);


            // check headers are valid **before** running the entire import process queue.
            Excel::import(new DavisFileHeaderValidation(), $headerValidationFile, 'public', \Maatwebsite\Excel\Excel::TSV);

            // for some reason, running Excel::import followed by Excel::queueImport on the same thread causes an error:
            // ("serialize(): &quot;spreadsheet&quot; returned as member variable from __sleep() but does not exist")

            // to get around this, we dispatch a new job that handles the setup of the Excel::queueImport() process...
            StartMetDataImport::dispatch($fileRecord, $fileWithMergedHeaders, Auth::user());

        }

        return response()->json(['started']);
    }

    /**
     * @throws Exception
     */
    public function status($upload_id)
    {

        return response([
            'started' => filled(cache("start_date_$upload_id")),
            'finished' => filled(cache("end_date_$upload_id")),
            'current_row' => cache("current_row_$upload_id"),
        ]);
    }


    public function checkValues($upload_id)
    {
        $error_date = [];
        $error_temp = false;
        $error_press = false;
        $error_wind = false;
        $error_rain = false;


        $daily_preview = DB::table('daily_data_preview')->where('uploader_id', '=', $upload_id)->get();

        foreach ($daily_preview as $key => $value) {

            $daily_temp_int = Daily::select('max_temperatura_interna')->whereMonth('fecha', substr($value->fecha, -5, -3))->whereDay('fecha', substr($value->fecha, -2))->take(10)->get();

            $daily_temp_ext = Daily::select('max_temperatura_interna')->whereMonth('fecha', substr($value->fecha, -5, -3))->whereDay('fecha', substr($value->fecha, -2))->take(10)->get();

            $daily_velocidad_viento = Daily::select('max_velocidad_viento')->whereMonth('fecha', substr($value->fecha, -5, -3))->whereDay('fecha', substr($value->fecha, -2))->take(10)->get();


            if (!$daily_temp_int->isEmpty()) {

                $checkTempInt = abs($daily_temp_int[0]['max_temperatura_interna'] - $value->max_temperatura_interna) > 20;

                $checkTempExt = abs($daily_temp_ext[0]['max_temperatura_externa'] - $value->max_temperatura_externa) > 20;

                $checkPresRel = $value->max_presion_relativa < 500;

                $checkViento = $value->max_velocidad_viento > 100 || $value->max_velocidad_viento > 2 * $daily_velocidad_viento[0]['max_velocidad_viento'];


                if ($checkTempInt || $checkTempExt) {
                    $error_temp = true;
                    array_push($error_date, $value->fecha);
                }
                if ($checkPresRel) {
                    $error_press = true;
                    array_push($error_date, $value->fecha);

                }
                if ($checkViento) {
                    $error_wind = true;
                    array_push($error_date, $value->fecha);
                }
            }
        }

        $error_data = MetDataPreview::whereIn('fecha_hora', $error_date)->where('uploader_id', '=', $upload_id)->get();

        return response([

            'error_data' => $error_data,
            'error_temp' => $error_temp,
            'error_press' => $error_press,
            'error_wind' => $error_wind,
            'error_rain' => $error_rain

        ]);

    }


    // enhancement: add datetime string YYYYMMDDHHMISS as prefix, make it as a unique string
    public function generateRandomString($length = 10)
    {
        // get current date time
        $currentTime = Carbon::now();

        // generate random string
        $random_string = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);

        // concatenate current date time and random string, it should be good enough to be a unique string
        return $currentTime->format('YmdHis') . $random_string;
    }


    // when user click "Cancel" button, remove staging records in table met_data_preview
    public function cancelUpload($upload_id)
    {

        // For traceability and consistency, do not remove met_data_preview records no matter user click "Cancel" or "Confirm" button
        // For housekeeping and performance concern, met_data_preview records older than 14 days will be removed by daily schedule job

        Alert::add('info', 'Carga cancelada: todos los datos de la vista previa se han eliminado de la base de datos')->flash();

        return Redirect::back();

    }


    // when user click "Confirm" button, run Python program to "move" staging records from
    // met_data_preview table to data table
    public function storeFile($upload_id)
    {
        $columns = collect(
            MetDataPreview::where('upload_id', $upload_id)
                ->first()
                ->getAttributes()
        )->keys();


        // remove upload_id from column list
        $columns = $columns->filter(fn($value, $key) => $value !=="upload_id" && $value !== "id");

        $newDataQuery = MetDataPreview::select($columns->toArray())->where('upload_id', $upload_id);


        //avoid pulling all records into memory, so do the transfer via db:
        // need to use raw SQL for the "IGNORE" part...
        DB::insert("INSERT IGNORE INTO met_data (" . $columns->join(', ') . ") " . $newDataQuery->toSql(), $newDataQuery->getBindings());


        // confirm records are in database;
        $fileRecord = File::firstWhere('upload_id', $upload_id);
        $metDataCount = MetData::where('file_id', $fileRecord->id)->count();


        // update file reference to mark it as successful, for future review;
        $fileRecord->update([
            'is_success' => 1
        ]);

        $maxDate = MetData::where('file_id', $fileRecord->id)->max('fecha_hora');
        $minDate = MetData::where('file_id', $fileRecord->id)->min('fecha_hora');

        $maxDate = (new Carbon($maxDate))->toDateString();
        $minDate = (new Carbon($minDate))->toDateString();

        $result = \DB::select(
            "call generate_daily_met_data_by_date_range(?, ?, ?);",
            [$minDate, $maxDate, $fileRecord->station_id]
        );


        Alert::add('success', "Carga completa. Todos los registros {$metDataCount} se almacenan en la base de datos y se han calculado los resúmenes diarios.")->flash();

        return Redirect::back();
    }

}
