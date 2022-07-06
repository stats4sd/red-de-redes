<?php

namespace App\Http\Controllers;

use App\Events\MetDataImportCompleted;
use App\Events\MetDataImportStarted;
use App\Http\Requests\FileRequest;
use App\Imports\DavisFileImport;
use App\Imports\PreProcessDavisHeaders;
use App\Jobs\MetDataImportCompletedJob;
use App\Models\Met\File;
use App\Models\Met\Station;
use DB;
use App\Models\Met\Daily;
use Exception;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Met\MetDataPreview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use JsonException;
use Maatwebsite\Excel\Facades\Excel;
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
            [$fileWithMergedHeaders, $count] = $processor($fileRecord->data_file);

            $fileRecord->update(['total_records_count' => $count]);

            MetDataImportStarted::dispatch($fileRecord, Auth::user());

            Excel::queueImport(new DavisFileImport($fileRecord), $fileWithMergedHeaders, 'public', \Maatwebsite\Excel\Excel::TSV)->chain([
                new MetDataImportCompletedJob($fileRecord, Auth::user())
            ]);

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

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
    public function cleanTable($upload_id)
    {
        DB::table('met_data_preview')->where('uploader_id', '=', $upload_id)->delete();

        return Redirect::back();

    }


    // when user click "Confirm" button, run Python program to "move" staging records from
    // met_data_preview table to data table
    public function storeFile($upload_id)
    {

        $scriptPath = base_path() . '/scripts/storeData.py';

        if (config('app.pipenv')) {
            $process = new Process(['pipenv', 'run', 'python3', $scriptPath, $upload_id]);
        } else {
            $process = new Process(['python', $scriptPath, $upload_id]);
        }

        $process->run();

        // write Python log message to Laravel log file
        Log::info($process->getOutput());


        if (!$process->isSuccessful()) {

            Log::error('process failed');
            Log::error($process->getErrorOutput());

            return response()->json(['error' => 'Los datos no se pueden guardar en la base de datos. Recomendamos verificar si hay duplicados']);

        }

        // update file reference to mark it as successful, for future review;
        File::whereUploadId($upload_id)->update([
            'is_success' => 1
        ]);

        $process->getOutput();

        return response()->json(['success' => 'Los datos han sido ingresados exitosamente.']);
    }

}
