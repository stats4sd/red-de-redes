<?php

namespace App\Http\Controllers;

use App\Imports\DavisFileImport;
use App\Imports\PreProcessDavisHeaders;
use App\Models\Met\File;
use App\Models\Met\Station;
use DB;
use App\Models\Met\Daily;
use App\Models\Met\Observation;
use Illuminate\Http\JsonResponse;
use \GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Met\MetDataPreview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\Process\Exception\ProcessFailedException;

class FileController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \JsonException
     */
    public function store(Request $request): JsonResponse
    {
        // TODO: Update with proper validation
        $station = $request->selectedStation ?? null ;

        if ($request->hasFile('data-filesObservation')) {
            // handle file and store it for prosperity
            $filesObservation = $request->file('data-filesObservation');
            $filesObservation_name = str_replace(" ", "_", $filesObservation->getClientOriginalName());
            $observation_name = time() . '_' . $filesObservation_name;
            $path = $filesObservation->storeAs('observationFiles', $observation_name);
            $newObservation = new Observation;
            $newObservation->files = $path;
            $newObservation->save();

            // refresh it from the database to retrieve ID
            $newObservation->refresh();
            $newObservation_id = $newObservation->id;

        }


        if ($request->hasFile('data-file')) {
            // handle file and store it for prosperity
            $file = $request->file('data-file');
            $file_name = str_replace(" ", "_", $file->getClientOriginalName());
            $name = time() . '_' . $file_name;
            $path = $file->storeAs('rawfiles', $name);

            // store the file entry in the database;
            $newFile = File::create([
                'path' => $path,
                'name' => $name,
                'station_id' => $station,
                'observation_id' => $newObservation_id ?? null,
                'uploader_id' => Auth::id(),
                'upload_id' => $this->generateRandomString(),
            ]);

            $fullPath = Storage::path("/") . $path;

            // upload data to met_data_preview table
            if(Station::find($station)->type === 'davis') {
                $processor = (new PreProcessDavisHeaders());
                $fileWithMergedHeaders = $processor($fullPath);

                Excel::import(new DavisFileImport($newFile->upload_id, $station), $fileWithMergedHeaders, null,\Maatwebsite\Excel\Excel::TSV);

            }


            // TODO: add Chinas file support

//            if (config('app.pipenv')) {
//                $isWindows = 0;
//                //dd(collect(['pipenv', 'run', 'python3', $scriptPath, $path_name, $station, $request->selectedUnitTemp, $request->selectedUnitPres, $request->selectedUnitWind, $request->selectedUnitRain, $upload_id, $isWindows, $newObservation_id])->join(" "));
//                $process = new Process(['pipenv', 'run', 'python3', $scriptPath, config('app.env'), $path_name, $station, $request->selectedUnitTemp, $request->selectedUnitPres, $request->selectedUnitWind, $request->selectedUnitRain, $newFile->upload_id, $isWindows, $newObservation_id]);
//            } else {
//                $isWindows = 1;
//                $process = new Process(['python', $scriptPath, config('app.env'), $path_name, $station, $request->selectedUnitTemp, $request->selectedUnitPres, $request->selectedUnitWind, $request->selectedUnitRain, $newFile->upload_id, $isWindows, $newObservation_id]);
//            }

//            $process->setWorkingDirectory(base_path());
//
//            $process->run();
//
////            Log::info($process->getOutput());
//
//            if (!$process->isSuccessful()) {
//                throw new ProcessFailedException($process);
//            }

            $metDataPreview = MetDataPreview::where('uploader_id', '=', $newFile->upload_id)->orderBy('id')->paginate(10);

            // $error_data = $this->checkValues($newFile->upload_id);


            // check number of uploaded records
            $sqlUploadedRecords = " SELECT COUNT(*) as number_of_records FROM met_data_preview WHERE uploader_id = '" . $newFile->upload_id . "';";

            // execute custom SELECT SQL
            $uploadedRecordsResults = DB::select($sqlUploadedRecords);
            $numberUploadedRecords = $uploadedRecordsResults[0]->number_of_records;


            // check number of records already existed in database
            $sqlExistedRecords = " SELECT COUNT(*) as number_of_records";
            $sqlExistedRecords .= " FROM met_data ta, met_data_preview tb";
            $sqlExistedRecords .= " WHERE tb.uploader_id = '" . $newFile->upload_id . "'";
            $sqlExistedRecords .= " AND ta.fecha_hora = tb.fecha_hora";
            $sqlExistedRecords .= " AND ta.station_id = tb.station_id;";

            // execute custom SELECT SQL
            $existedRecordsResults = DB::select($sqlExistedRecords);
            $numberExistedRecords = $existedRecordsResults[0]->number_of_records;


            // number of not existed records = number of uploaded records - number of existed records
            $numberNotExistedRecords = $numberUploadedRecords - $numberExistedRecords;

            // update file record
            $newFile->update([
                'new_records_count' => $numberNotExistedRecords,
                'duplicate_records_count' => $numberExistedRecords,
            ]);


            // prepare advice message
            $scenario = 0;
            $adviceMessage = "";

            if ($numberNotExistedRecords == $numberUploadedRecords) {
                $scenario = 1;
                $adviceMessage = "All " . $numberUploadedRecords . " record(s) are new records. Please kindly confirm to upload this data file.";
            } else if ($numberExistedRecords == $numberUploadedRecords) {
                $scenario = 2;
                $adviceMessage = "All " . $numberExistedRecords . " record(s) are already existed in system. Please kindly cancel this upload.";
            } else {
                $scenario = 3;
                $adviceMessage = $numberExistedRecords . " out of " . $numberUploadedRecords . " records are already existed in system. Please kindly tick below checkbox to confirm uploading non existed records or cancel this upload to further check data file correctness.";
            }


            return response()->json([
                'met_data_preview' => $metDataPreview,
                'number_uploaded_records' => $numberUploadedRecords,
                'number_existed_records' => $numberExistedRecords,
                'number_not_existed_records' => $numberNotExistedRecords,
                'scenario' => $scenario,
                'adviceMessage' => $adviceMessage,
                'error_data' => null

            ]);
        }

        abort(500, 'request did not contain a file - please check that the file was correctly attached');


        // Send file onto cloud function
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

        $error_data = MetDataPreview::whereIn('fecha_hora',$error_date)->where('uploader_id', '=', $upload_id)->get();

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
