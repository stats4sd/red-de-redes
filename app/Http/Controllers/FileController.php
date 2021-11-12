<?php
namespace App\Http\Controllers;

use DB;
use Alert;
use Excel;
use App\File;
use App\Models\Daily;
use \GuzzleHttp\Client;
use App\Models\Observation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\DailyDataPreview;
use App\Models\WeatherDataPreview;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\Process\Exception\ProcessFailedException;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $station = $request->selectedStation;
        if($request->hasFile('data-filesObservation')){
            // handle file and store it for prosperity
            $filesObservation = $request->file('data-filesObservation');
            $filesObservation_name = str_replace(" ", "_", $filesObservation->getClientOriginalName());
            $observation_name = time() . '_' . $filesObservation_name;
            $path = $filesObservation->storeAs('observationFiles', $observation_name);
            $newObservation = new Observation;
            $newObservation->files = $path;
            $newObservation->save();
        }


        if($request->hasFile('data-file')){
            // handle file and store it for prosperity
            $file = $request->file('data-file');
            $file_name = str_replace(" ", "_", $file->getClientOriginalName());
            $name = time() . '_' . $file_name;
            $path = $file->storeAs('rawfiles',$name);
            $newFile = new File;
            $newFile->path = $path;
            $newFile->name = $name;
            $newFile->station_id = $station;
            $newFile->save();
            $scriptName = 'uploadDatapreview.py';
            $scriptPath = base_path() . '/scripts/' . $scriptName;
            $path_name = Storage::path("/").$path;
            $uploader_id = $this->generateRandomString();
            
            //python script accepts 3 arguments in this order: scriptPath, path_name, station_id
            if($request->hasFile('data-filesObservation')){
                $newObservation_id = $newObservation->id;
            }else {
                $newObservation_id = "null";
            }

            //$process = new Process(['pipenv', 'run', 'python3', $scriptPath, $path_name, $station, $request->selectedUnitTemp, $request->selectedUnitPres, $request->selectedUnitWind, $request->selectedUnitRain, $uploader_id, $newObservation_id]);
            $process = new Process(['python', $scriptPath, $path_name, $station, $request->selectedUnitTemp, $request->selectedUnitPres, $request->selectedUnitWind, $request->selectedUnitRain, $uploader_id, $newObservation_id]);
            

            $process->run();

            Log::info($process->getOutput());

            if(!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
    
            $data_template = WeatherDataPreview::where('uploader_id', '=', $uploader_id)->orderBy('id')->paginate(10);
            
            // $error_data = $this->checkValues($uploader_id);


            // check number of uploaded records
            $sqlUploadedRecords = " SELECT COUNT(*) as number_of_records FROM data_template WHERE uploader_id = '" . $uploader_id . "';";

            // execute custom SELECT SQL
            $uploadedRecordsResults = DB::select($sqlUploadedRecords);
            $numberUploadedRecords = $uploadedRecordsResults[0]->number_of_records;

            
            // check number of records already existed in database
            $sqlExistedRecords  = " SELECT COUNT(*) as number_of_records";
            $sqlExistedRecords .= " FROM data ta, data_template tb";
            $sqlExistedRecords .= " WHERE tb.uploader_id = '" . $uploader_id . "'";
            $sqlExistedRecords .= " AND ta.fecha_hora = tb.fecha_hora";
            $sqlExistedRecords .= " AND ta.id_station = tb.id_station;";

            // execute custom SELECT SQL
            $existedRecordsResults = DB::select($sqlExistedRecords);
            $numberExistedRecords = $existedRecordsResults[0]->number_of_records;

            
            // number of not existed records = number of uploaded records - number of existed records
            $numberNotExistedRecords = $numberUploadedRecords - $numberExistedRecords;



            // prepare advice message
            $adviceMessage = "";
            $flagUploadable = 0;

            if ($numberNotExistedRecords == $numberUploadedRecords) {
                $adviceMessage = "All " . $numberUploadedRecords . " record(s) are new records. Please kindly confirm to upload this data file.";
                $flagUploadable = 1;
            } else if ($numberExistedRecords == $numberUploadedRecords) {
                $adviceMessage = "All " . $numberExistedRecords .  " record(s) are already existed in system. Please kindly cancel this upload.";
                $flagUploadable = 0;
            } else {
                $adviceMessage = $numberExistedRecords . " out of " . $numberUploadedRecords . " records are already existed in system. Please kindly cancel this upload and further check data file correctness.";
                $flagUploadable = 0;
            }


            return response()->json([
                'data_template' => $data_template,
                'number_uploaded_records' => $numberUploadedRecords,
                'number_existed_records' => $numberExistedRecords,
                'number_not_existed_records' => $numberNotExistedRecords,
                'adviceMessage' => $adviceMessage,
                'flagUploadable' => $flagUploadable,
                'error_data' => null

            ]);
        }

        abort(500, 'request did not contain a file - please check that the file was correctly attached');



        // Send file onto cloud function
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function checkValues($uploader_id)
    {
        $error_date = [];
        $error_temp = false;
        $error_press = false;
        $error_wind = false;
        $error_rain = false;

       
        $daily_preview = DB::table('daily_data_preview')->where('uploader_id', '=', $uploader_id)->get();
        
        foreach ($daily_preview as $key => $value) {

            $daily_temp_int = Daily::select('max_temperatura_interna')->whereMonth('fecha',  substr($value->fecha, -5, -3))->whereDay('fecha', substr($value->fecha, -2))->take(10)->get();

            $daily_temp_ext = Daily::select('max_temperatura_interna')->whereMonth('fecha',  substr($value->fecha, -5, -3))->whereDay('fecha', substr($value->fecha, -2))->take(10)->get();

             $daily_velocidad_viento = Daily::select('max_velocidad_viento')->whereMonth('fecha',  substr($value->fecha, -5, -3))->whereDay('fecha', substr($value->fecha, -2))->take(10)->get();


            if(!$daily_temp_int->isEmpty()){

                $checkTempInt = abs($daily_temp_int[0]['max_temperatura_interna'] - $value->max_temperatura_interna) > 20;

                $checkTempExt = abs($daily_temp_ext[0]['max_temperatura_externa'] - $value->max_temperatura_externa) > 20;

                $checkPresRel = $value->max_presion_relativa < 500;

                $checkViento = $value->max_velocidad_viento > 100 || $value->max_velocidad_viento > 2*$daily_velocidad_viento[0]['max_velocidad_viento'] ;


                if($checkTempInt || $checkTempExt){
                    $error_temp = true;
                    array_push($error_date, $value->fecha);
                }if($checkPresRel){
                    $error_press = true;
                    array_push($error_date, $value->fecha);

                }if($checkViento){
                    $error_wind = true;
                    array_push($error_date, $value->fecha);
                }
            }
        }

        $error_data = WeatherDataPreview::whereIn('fecha_hora',$error_date)->where('uploader_id', '=', $uploader_id)->get();

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
        $random_string = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
        
        // concatenate current date time and random string, it should be good enough to be a unique string
        return $currentTime->format('YmdHis') . $random_string;
    }


    // when user click "Cancel" button, remove staging records in table data_template
    public function cleanTable($uploader_id)
    {
        DB::table('data_template')->where('uploader_id', '=', $uploader_id)->delete();
        
        return Redirect::back();

    }


    // when user click "Confirm" button, run Python program to "move" staging records from 
    // data_template table to data table
    public function storeFile($uploader_id)
    {

        $scriptPath = base_path() . '/scripts/storeData.py';

        //$process = new Process(['pipenv', 'run', 'python3', $scriptPath, $uploader_id]);
        $process = new Process(['python', $scriptPath, $uploader_id]);

        $process->run();


        if(!$process->isSuccessful()) {

           throw new ProcessFailedException($process);

            return response()->json(['error' => 'Los datos no se pueden guardar en la base de datos. Recomendamos verificar si hay duplicados']);

        } else {

            $process->getOutput();

            return response()->json(['success' => 'Los datos han sido ingresados ​​exitosamente.']);
        }
        Log::info("python done.");
        Log::info($process->getOutput());


        return Redirect::back();

    }

}
