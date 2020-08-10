<?php
namespace App\Http\Controllers;

use Alert;
use App\File;
use DB;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use \GuzzleHttp\Client;
use App\Models\DataTemplate;
use App\Models\DailyDataPreview;
use App\Models\Daily;


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

            $process = new Process("pipenv run python3 {$scriptPath} {$path_name} {$station} {$request->selectedUnitTemp} {$request->selectedUnitPres} {$request->selectedUnitWind} {$request->selectedUnitRain} {$uploader_id}");

            $process->setTimeout(500);

            $process->run();

            if(!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $data_template = DataTemplate::paginate(10)->where('uploader_id', '=', $uploader_id);
            
            $error_data = $this->checkValues($uploader_id);

            return response()->json([
                'data_template' => $data_template,
                'error_data' => $error_data

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

            $daily_temp_int = Daily::select('max_temperatura_interna')->whereMonth('fecha',  substr($value->fecha, -5, -3))->whereDay('fecha', substr($value->fecha, -2))->get();

            $daily_temp_ext = Daily::select('max_temperatura_interna')->whereMonth('fecha',  substr($value->fecha, -5, -3))->whereDay('fecha', substr($value->fecha, -2))->get();

             $daily_velocidad_viento = Daily::select('max_velocidad_viento')->whereMonth('fecha',  substr($value->fecha, -5, -3))->whereDay('fecha', substr($value->fecha, -2))->get();


            if(!$daily_temp_int->isEmpty()){

                $checkTempInt = abs($daily_temp_int[0]['max_temperatura_interna'] - $value->max_temperatura_interna) > 15;

                $checkTempExt = abs($daily_temp_ext[0]['max_temperatura_externa'] - $value->max_temperatura_externa) > 15;

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

        $error_data = DataTemplate::whereIn('fecha_hora',$error_date)->where('uploader_id', '=', $uploader_id)->get();

        return response([

            'error_data' => $error_data,
            'error_temp' => $error_temp,
            'error_press' => $error_press,
            'error_wind' => $error_wind,
            'error_rain' => $error_rain

        ]);

    }

    public function generateRandomString($length = 10) 
    { 
        $random_string = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
        return $random_string;
    }

    public function cleanTable($uploader_id)
    {
        DB::table('data_template')->where('uploader_id', '=', $uploader_id)->delete();
        
        return Redirect::back();

    }

    public function storeFile($uploader_id)
    {

        $scriptPath = base_path() . '/scripts/storeData.py';

        $process = new Process("pipenv run python3 {$scriptPath} {$uploader_id}");

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
