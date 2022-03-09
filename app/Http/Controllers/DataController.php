<?php

namespace App\Http\Controllers;

use DB;
use ZipArchive;
use Carbon\Carbon;
use App\Models\Met\MetData;
use Illuminate\Support\Str;
use App\Models\DataTemplate;
use Illuminate\Http\Request;
use App\Models\Met\Station;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('data_preview');
    }

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $weather = [];
        $parcelas = [];
        $suelos = [];
        $manejo_parcelas = [];
        $plagas = [];
        $enfermedades = [];
        $rendimentos = [];
        $fenologia = [];
        $senamhi_data = [];
        $station = Station::findOrFail($request->stationsSelected)->first();
        foreach ($request->modulesSelected as $module) {
            if ($module=='daily_data') {
                if ($request->aggregationSelected=='daily_data') {
                    $weather = DB::table('daily_data')->where('fecha', '>=', $request->startDate)->where('fecha', '<=', $request->endDate)->whereIn('station_id', $request->stationsSelected)->paginate(100);
                } elseif ($request->aggregationSelected=='tendays_data') {
                    $weather = DB::table($request->aggregationSelected)->where('max_fecha', '>=', $request->startDate)->where('min_fecha', '<=', $request->endDate)->whereIn('station_id', $request->stationsSelected)->paginate(5);
                } elseif ($request->aggregationSelected=="monthly_data") {
                    $weather = DB::table($request->aggregationSelected)->where('fecha', '>=', $request->startDate)->where('fecha', '<=', $request->endDate)->whereIn('station_id', $request->stationsSelected)->paginate(5);
                } elseif ($request->aggregationSelected=="yearly_data") {
                    $weather = DB::table($request->aggregationSelected)->where('fecha', '>=', $request->startDate)->where('fecha', '<=', $request->endDate)->whereIn('station_id', $request->stationsSelected)->paginate(5);
                } elseif ($request->aggregationSelected=="senamhi_daily") {
                    $senamhi = DB::table('daily_data')->whereYear('fecha', $request->yearSelected)->select(DB::raw('MONTH(fecha) month, DAY(fecha) day'), $request->meteoParameterSelected)->where('station_id', $request->stationsSelected)->orderBy('day', 'asc')->get();

                    foreach ($senamhi as $month) {
                        $month_name = date('F', mktime(0, 0, 0, $month->month, 10));
                        $short_name = strtoupper(substr($month_name, 0, 3));
                        $parameter = array_keys((array)$month)[2];
                        $month->$short_name = $month->$parameter;
                    }


                    $senamhi = $senamhi->groupBy('day');

                    $senamhi_data = [];
                    $data = (object)[];

                    foreach ($senamhi as $days) {
                        foreach ($days as $day) {
                            $short_month = array_keys((array)$day)[3];
                            $data->day = $day->day;
                            $data->$short_month = $day->$short_month;
                        }

                        $senamhi_data[]=(array)$data;
                    }
                } else {
                    $senamhi = DB::table('monthly_data')->where('station_id', $request->stationsSelected)->whereBetween('year', [$request->yearInitialSelected, $request->yearFinalSelected])->whereBetween('month', [$request->monthInitialSelected, $request->monthFinalSelected])->select('year', 'month', $request->meteoParameterSelected)->orderBy('year', 'asc')->get();

                    foreach ($senamhi as $month) {
                        $month_name = date('F', mktime(0, 0, 0, $month->month, 10));
                        $short_name = strtoupper(substr($month_name, 0, 3));
                        $parameter = array_keys((array)$month)[2];
                        $month->$short_name = $month->$parameter;
                    }

                    $senamhi = $senamhi->groupBy('year');
                    $senamhi_data = [];
                    $data = (object)[];
                    foreach ($senamhi as $year) {
                        foreach ($year as $month) {
                            $short_month = array_keys((array)$month)[3];
                            $data->year = $month->year;
                            $data->$short_month = $month->$short_month;
                        }
                        $senamhi_data[]=(array)$data;
                    }
                }
            }
        }

        return response()->json([
            'station'=> $station,
            'weather' => $weather,
            'senamhi' => $senamhi_data,
            'parcelas' => $parcelas,
            'suelos' => $suelos,
            'manejo_parcelas' => $manejo_parcelas,
            'plagas' => $plagas,
            'enfermedades' => $enfermedades,
            'rendimentos' => $rendimentos,
            'fenologia' => $fenologia,
        ]);
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

    public function download(Request $request)
    {
        $scriptPath = base_path() . '/scripts/generate_xlsx_from_query.py';
        $base_path = base_path();
        $file_name = date('c')."Agrometric.xlsx";

        $queries = '';
        $sheet_names = '';

        foreach ($request->modulesSelected as $module) {
            if ($module=='daily_data') {
                if ($request->aggregationSelected=='tendays_data') {
                    $query = "select * from ". $request->aggregationSelected . " where max_fecha >= '".$request->startDate."' and max_fecha <= '".$request->endDate."' and station_id in (". implode(",", $request->stationsSelected).");";
                } else {
                    $query = "select * from ". $request->aggregationSelected . " where fecha >= '".$request->startDate."' and fecha <= '".$request->endDate."' and station_id in (". implode(",", $request->stationsSelected).");";
                }





                $queries = $queries.$query;
                $sheet_names = $sheet_names.$request->aggregationSelected.', ';
            }
            if ($module=='parcelas') {
                $query = "select * from ". 'parcela' . " where comunidad_id in (". implode(",", $request->comunidadsSelected).");";

                $queries = $queries.$query;
                $sheet_names = $sheet_names.'parcelas, ';
                foreach ($request->parcelasModulesSelected as $parcelas_modules) {
                    if ($parcelas_modules=='suelos') {
                        $query = "select * from ". 'suelo' . " where comunidad_id in (". implode(",", $request->comunidadsSelected).");";

                        $queries = $queries.$query;
                        $sheet_names = $sheet_names.'suelos, ';
                    }
                    if ($parcelas_modules=='manejo_parcelas') {
                        $query = "select * from ". 'manejo_parcela' . " where comunidad_id in (". implode(",", $request->comunidadsSelected).");";

                        $queries = $queries.$query;
                        $sheet_names = $sheet_names.'manejo_parcelas, ';
                    }
                    if ($parcelas_modules=='plagas') {
                        $query = "select * from ". $parcelas_modules . " where comunidad_id in (". implode(",", $request->comunidadsSelected).");";

                        $queries = $queries.$query;
                        $sheet_names = $sheet_names.'plagas, ';
                    }
                    if ($parcelas_modules=='enfermedades') {
                        $query = "select * from ". $parcelas_modules . " where comunidad_id in (". implode(",", $request->comunidadsSelected).");";

                        $queries = $queries.$query;
                        $sheet_names = $sheet_names.'enfermedades, ';
                    }
                    if ($parcelas_modules=='rendimentos') {
                        $query = "select * from ". $parcelas_modules . " where comunidad_id in (". implode(",", $request->comunidadsSelected).");";

                        $queries = $queries.$query;
                        $sheet_names = $sheet_names.'rendimentos, ';
                    }
                }
            }
            if ($module=='cultivos') {
                foreach ($request->cultivosModulesSelected as $cultivo_modules) {
                    if ($cultivo_modules=='fenologia') {
                        $query = "select * from ". $cultivo_modules . " where comunidad_id in (". implode(",", $request->comunidadsSelected).");";

                        $queries = $queries.$query;
                        $sheet_names = $sheet_names.'fenologia, ';
                    }
                    if ($cultivo_modules=='manejo_parcelas') {
                        $query = "select * from ". 'manejo_parcela' . " where comunidad_id in (". implode(",", $request->comunidadsSelected).");";

                        $queries = $queries.$query;
                        $sheet_names = $sheet_names.'manejo_parcelas, ';
                    }
                    if ($cultivo_modules=='plagas') {
                        $query = "select * from ". $cultivo_modules . " where comunidad_id in (". implode(",", $request->comunidadsSelected).");";

                        $queries = $queries.$query;
                        $sheet_names = $sheet_names.'plagas, ';
                    }
                    if ($cultivo_modules=='enfermedades') {
                        $query = "select * from ". $cultivo_modules . " where comunidad_id in (". implode(",", $request->comunidadsSelected).");";

                        $queries = $queries.$query;
                        $sheet_names = $sheet_names.'plagas, ';
                    }
                    if ($cultivo_modules=='rendimentos') {
                        $query = "select * from ". $cultivo_modules . " where comunidad_id in (". implode(",", $request->comunidadsSelected).");";

                        $queries = $queries.$query;
                        $sheet_names = $sheet_names.'rendimentos, ';
                    }
                }
            }
        }

        $queries = rtrim($queries, ";");
        $sheet_names = rtrim($sheet_names, ", ");

        #python script accepts 4 arguments in this order: base_path(), queries in string, file name and sheet names in string

        $process = new Process(["pipenv", "run", "python3", $scriptPath, $base_path, $queries, $file_name, $sheet_names]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        } else {
            $process->getOutput();
        }

        #Create Zip Archive for observation files.
        $weather_observation = MetData::whereHas('observation')->with('observation')->where('station_id', $request->stationsSelected)->whereBetween('fecha_hora', [$request->startDate, $request->endDate])->get();

        $list_files = array();
        foreach ($weather_observation as $observation) {
            if (!in_array($observation->observation->files, $list_files)) {
                array_push($list_files, $observation->observation->files);
            }
        }

        $zip = new ZipArchive();

        $zip_name = Str::slug('Agronometric'.'_'.Carbon::now()->toDateTimeString());

        $zip->open(storage_path("app/public/data/{$zip_name}_files.zip"), ZipArchive::CREATE);

        foreach ($list_files as $file) {
            $split_filename = explode('/', $file);
            $original_filename = $split_filename[1];

            $zip->addFile(public_path('/storage/'.$file), "Observation files/{$original_filename}");
        }

        #Add file with Agronometric Data

        $zip->addFile(public_path('/storage/data/'.$file_name), "{$file_name}");
        $zip->close();

        $path_download =  Storage::url('/data/'.$zip_name.'_files.zip');
        return response()->json(['path' => $path_download]);
    }
}
