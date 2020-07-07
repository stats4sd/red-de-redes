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
        // Retrieve file from POST request
        //sends units type to DataTemplate
        // Session::put('temp_unit', $_POST['temp_unit']);
        // Session::put('pression_unit', $_POST['pression_unit']);
        // Session::put('veloc_viento_unit', $_POST['veloc_viento_unit']);
        // Session::put('precip_unit', $_POST['precip_unit']);

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

            
            
        
        //python script accepts 3 arguments in this order: scriptPath, path_name, station_id

        $process = new Process("python3 {$scriptPath} {$path_name} {$station}");

        $process->setTimeout(300);
        
        $process->run();
        
        if(!$process->isSuccessful()) {
            
           throw new ProcessFailedException($process);
           \Alert::success('<h4>'.$process->getMessage().'</h4>')->flash();
        
        } 
    }
        

        $data_template = DataTemplate::paginate(5);
    
        $error_data = $this->checkValues();
      
        
        return response()->json([
            'data_template' => $data_template, 
            'error_data' => $error_data
           
        ]);

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

    public function checkValues()
    {
        $error_date = [];
      
        $daily_preview = DailyDataPreview::all();
        foreach ($daily_preview as $key => $value) {
     
            $daily_temp_int = Daily::select('max_temperatura_interna')->whereMonth('fecha',  substr($value->fecha, -5, -3))->whereDay('fecha', substr($value->fecha, -2))->get();

            $daily_temp_ext = Daily::select('max_temperatura_interna')->whereMonth('fecha',  substr($value->fecha, -5, -3))->whereDay('fecha', substr($value->fecha, -2))->get();

             $daily_velocidad_viento = Daily::select('max_velocidad_viento')->whereMonth('fecha',  substr($value->fecha, -5, -3))->whereDay('fecha', substr($value->fecha, -2))->get();

           
            if(!$daily_temp_int->isEmpty()){
            
           
               
                $checkTempInt = abs($daily_temp_int[0]['max_temperatura_interna'] - $value->max_temperatura_interna) > 15;

                $checkTempExt = abs($daily_temp_ext[0]['max_temperatura_externa'] - $value->max_temperatura_externa) > 15;

                $checkPresRel = $value->max_presion_relativa < 500;

                $checkViento = $value->max_velocidad_viento > 100 || $value->max_velocidad_viento > 2*$daily_velocidad_viento[0]['max_velocidad_viento'] ;

              
                if($checkTempInt || $checkTempExt || $checkPresRel || $checkViento){

                    array_push($error_date, $value->fecha);
                }
            }
        }

        $error_data = DataTemplate::whereIn('fecha_hora',$error_date)->get();
      
        return $error_data; 

    }

    
}
