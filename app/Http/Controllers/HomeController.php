<?php

namespace App\Http\Controllers;

use App\Daily;
use App\Data;
use App\Exports\DailyExport;
use App\Exports\DataExport;
use App\Exports\MonthlyExport;
use App\Exports\TendaysExport;
use App\Exports\YearlyExport;
use App\Http\Controllers\selectStation;
use App\Monthly;
use App\Tendays;
use App\Yearly;
use DB;
use Illuminate\Http\Request;
use Khill\Lavacharts\Lavacharts;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;





class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */


    public function index ()

    {
        $station_data = DB::table('data')->get();
        $stations = DB::table('stations')->get();
        $daily = Daily::all();
        $monthly = Monthly::all();
        $tendays = Tendays::all();
        $yearly = Yearly::all();


        $lava= new Lavacharts;
        if(!empty($station_data)){
            $data=Daily::select("fecha as 0","max_temperatura_interna as 1", "avg_temperatura_interna as 2","min_temperatura_interna as 3")->get()->toArray();
            $datatable = $lava->DataTable();

            $datatable -> addDateColumn('Date')
                        -> addNumberColumn('Max Temp Int')
                        -> addNumberColumn('Mean Temp Int')
                        -> addNumberColumn('Min Temp Int')
                        -> addRows($data);

            $lava->LineChart('Temperature', $datatable,[
                    'title' => 'DailyTemperatures'
            ]);
        }


        return view('home')->with(compact('station_data','stations','daily','monthly','yearly','tendays','lava'));


    }


    public function graphicsData(Request $request)
    {
        $graphics_id = $request->input('graphics_id');
        $station_data = DB::table('data')->get();

        return $graphics_id;

    }

    public function excel(Request $request)
    {

        $stationSelected = $request->input('stationSelected');
        $periodSelected = $request->input('periodSelected');

        if($periodSelected =='1'){
            return Excel::download(new DailyExport($stationSelected), 'DailyData.csv');

        }else if($periodSelected == '2'){
            return Excel::download(new TendaysExport($stationSelected), 'TendaysData.csv');

        }else if($periodSelected == '3'){
            return Excel::download(new MonthlyExport($stationSelected), 'MontlyData.csv');

        }else if($periodSelected == '4'){
            return Excel::download(new YearlyExport($stationSelected), 'YearlyData.csv');
        }

    }

    public function excelData(Request $request)
    {
        $stationSelected = $request->input('stationSelected');

        if($stationSelected==["1"]){
            return Excel::download(new DataExport($stationSelected), 'Chojñapata-Davis.csv');
        }else if($stationSelected==["2"]){
            return Excel::download(new DataExport($stationSelected), 'Chinchaya-Davis.csv');
        }else if($stationSelected==["3"]){
            return Excel::download(new DataExport($stationSelected), 'Chinchaya-Chinas.csv');
        }else if($stationSelected==["4"]){
            return Excel::download(new DataExport($stationSelected), 'Calahuancane-Davis.csv');
        }else if($stationSelected==["5"]){
            return Excel::download(new DataExport($stationSelected), 'Cutusuma-Davis.csv');
        }else if($stationSelected==["6"]){
            return Excel::download(new DataExport($stationSelected), 'Iñacamaya-Davis.csv');
        }else if($stationSelected==["7"]){
            return Excel::download(new DataExport($stationSelected), 'Incamya-Chinas.csv');
        }
    }



    public function getDaily()
    {
        return DataTables::of(Daily::query())->make(true);
    }

    public function getTenDays()
    {
        return DataTables::of(Tendays::query())->make(true);
    }

    public function getMonthly()
    {
        return DataTables::of(Monthly::query())->make(true);
    }

     public function getYearly()
    {
        return DataTables::of(Yearly::query())->make(true);
    }

    public function getGraphic(Request $request)
    {
        $graphics_id = $request->input('graphics_id');

        if($graphics_id=='1'){
        $lava= new Lavacharts;

                $data=Daily::select("fecha as 0","max_temperatura_interna as 1", "avg_temperatura_interna as 2","min_temperatura_interna as 3")->get()->toArray();
                $datatable = $lava->DataTable();

                $datatable -> addDateColumn('Date')
                            -> addNumberColumn('Max Temp Int')
                            -> addNumberColumn('Mean Temp Int')
                            -> addNumberColumn('Min Temp Int')
                            -> addRows($data);


                $lava->LineChart('Temperature', $datatable,[
                        'title' => 'DailyTemperatures'

                ]);

        }
        return $datatable->toJson();

    }

}


