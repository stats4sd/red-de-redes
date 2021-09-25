<?php

namespace App\Http\Controllers;

use App\Dashboard;
use App\Models\Daily;
use App\Models\Monthly;
use App\Models\TenDays;
use App\Models\Yearly;
use App\Models\Station;
use Illuminate\Http\Request;
use stdClass;

class DashboardController extends Controller
{
    public function index ()
    {
        // removing ref to db until we can sort out how to not make the mysql views take ages to load...

        //$yearsdb = Yearly::select('fecha')->orderBy('fecha')->get()->unique('fecha');

        //******* TEMP CODE TO GENERATE STATIC LIST OF YEARS *******/
        $years = collect();

        for($i = 2006; $i<2022; $i++) {
            $newYear = new stdClass();
            $newYear->fecha = $i;
            $years->push($newYear);
        }

        $stations = Station::orderBy('id')->get();
        return view('dashboard', compact('years', 'stations'));
    }

    public function charts(Request $request)
    {
        $id = $request->station_id;
        $aggr = $request->agg;
        $year = $request->year;
        $month = $request->month;
        if($aggr === 'daily'){
            $data = Daily::whereYear('fecha','=',$year)->whereMonth('fecha', $month)->where('id_station', $id)->orderBy('fecha')->get();
        }elseif ($aggr === 'ten_days') {
            $data = TenDays::whereYear('min_fecha','=',$year)->where('id_station', $id)->orderBy('min_fecha')->get(['min_fecha AS fecha', 'tendays_data.*']);
        }elseif ($aggr === 'monthly') {
            $data = Monthly::where('year','=',$year)->orderBy('month')->where('id_station', $id)->get();
        }elseif ($aggr === 'yearly') {
            $data = Yearly::where('id_station', $id)->orderBy('fecha')->get();
        }


        return response()->json([
            'data' => $data
            ]);

    }





}
