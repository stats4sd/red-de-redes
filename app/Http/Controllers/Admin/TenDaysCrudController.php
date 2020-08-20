<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TenDaysRequest as StoreRequest;
use App\Http\Requests\TenDaysRequest as UpdateRequest;
use App\Jobs\ProcessDataExport;
use App\Models\Station;
use Backpack\CRUD\CrudPanel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Class TenDaysCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TenDaysCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        CRUD::setModel('App\Models\TenDays');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/tenDays');
        CRUD::setEntityNameStrings('tendays', 'tendays');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->operation('list', function() {

            $this->crud->addColumns([
                [
                    'label' => 'Max Fecha',
                    'name' => 'max_fecha',
                    'type' => 'date',
                ],
                [
                    'label' => 'Min Fecha',
                    'name' => 'min_fecha',
                    'type' => 'date',
                ],
                [
                    'label' => 'Station',
                    'type' => 'select',
                    'name' => 'id_station',
                    'entity' => 'station',
                    'attribute' => 'label',
                    'model' => 'App\Models\Station',
                    'key' => 'updated_at'
                ],
                [
                    'label' => 'Max Temp Int',
                    'name' => 'max_temperatura_interna',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Min Temp Int',
                    'name' => 'min_temperatura_interna',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Avg Temp Int',
                    'name' => 'avg_temperatura_interna',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Max Temp Ext',
                    'name' => 'max_temperatura_externa',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Min Temp Ext',
                    'name' => 'min_temperatura_externa',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Avg Temp Ext',
                    'name' => 'avg_temperatura_externa',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Max Hum Int',
                    'name' => 'max_humedad_interna',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Min Hum Int',
                    'name' => 'min_humedad_interna',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Avg Hum Int',
                    'name' => 'avg_humedad_interna',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Max Hum Ext',
                    'name' => 'max_humedad_externa',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Min Hum Ext',
                    'name' => 'min_humedad_externa',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Avg Hum Ext',
                    'name' => 'avg_humedad_externa',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Max Pres Rel',
                    'name' => 'max_presion_relativa',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Min Pres Rel',
                    'name' => 'min_presion_relativa',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Avg Pres Rel',
                    'name' => 'avg_presion_relativa',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Max Pres Abs',
                    'name' => 'max_presion_absoluta',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Min Pres Abs',
                    'name' => 'min_presion_absoluta',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Avg Pres Abs',
                    'name' => 'avg_presion_absoluta',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Max Sen térm',
                    'name' => 'max_sensacion_termica',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Min Sen térm',
                    'name' => 'min_sensacion_termica',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Avg Sen térm',
                    'name' => 'avg_sensacion_termica',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Max Sen térm',
                    'name' => 'max_velocidad_viento',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Min Vel Viento',
                    'name' => 'min_velocidad_viento',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'Avg Vel Viento',
                    'name' => 'avg_velocidad_viento',
                    'type' => 'decimal',

                ],
                [
                    'label' => 'lluvia 24 h',
                    'name' => 'lluvia_24_horas_total',
                    'type' => 'decimal',

                ],

            ]);
           #$this->crud->setFromDb();


        });

        // add asterisk for fields that are required in TenDaysRequest

        $this->crud->addButtonFromView('top', 'download', 'download', 'end');

        // Filter
        $this->crud->addFilter([
            'name' => 'id_station',
            'type' => 'select2',
            'label' => 'Station',
        ],function(){

            return Station::all()->pluck('label', 'id')->toArray();;

        },function($value){
            $this->crud->addClause('where', 'id_station', $value);

        });

        $this->crud->addFilter([ // date filter
          'type' => 'date',
          'name' => 'date',
          'label'=> 'Date'
        ],
        false,
        function($value) { // if the filter is active, apply these constraints
          $this->crud->addClause('where', 'max_fecha', $value);
        });

        $this->crud->addFilter([ // daterange filter
           'type' => 'date_range',
           'name' => 'from_to',
           'label'=> 'Date range'
        ],
        false,
        function($value) { // if the filter is active, apply these constraints
           $dates = json_decode($value);
           $this->crud->addClause('where', 'max_fecha', '>=', $dates->from);
           $this->crud->addClause('where', 'max_fecha', '<=', $dates->to . ' 23:59:59');
        });

        if($this->crud->actionIs('list') || $this->crud->actionIs('search') ){
            $tendays_query = $this->crud->query->getQuery()->toSql();
            $tendays_params = $this->crud->query->getQuery()->getBindings();
            Session(['tendays_query' => $tendays_query ]);
            Session(['tendays_params' => $tendays_params ]);

        }
    }

    public function download(Request $request)
    {
        $scriptName = 'save_data_csv.py';
        $scriptPath = base_path() . '/scripts/' . $scriptName;
        $base_path = base_path();
        $query = Session('tendays_query');
        $params = join(",",Session('tendays_params'));
        $query = '"'.$query.'"';
        $params = '"'.$params.'"';
        $file_name = date('c')."tendays.csv";
        $query = str_replace('`',' ',$query);

        //python script accepts 4 arguments in this order: base_path(), query, params and file name
        Log::info($query);

        $process = new Process("pipenv run python3 {$scriptPath} {$base_path} {$query} {$params} {$file_name}");

        $process->run();

        if(!$process->isSuccessful()) {

           throw new ProcessFailedException($process);

        } else {

            $process->getOutput();
        }
        Log::info("python done.");
        Log::info($process->getOutput());

        $path_download =  Storage::url('/data/'.$file_name);
        return response()->json(['path' => $path_download]);
    }


}
