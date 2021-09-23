<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Monthly_dataRequest as StoreRequest;
use App\Http\Requests\Monthly_dataRequest as UpdateRequest;
use App\Jobs\ProcessDataExport;
use App\Models\Monthly;
use App\Models\Station;
use App\Models\Yearly;
use Backpack\CRUD\CrudPanel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Class Monthly_dataCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MonthlyCrudController extends CrudController
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
        CRUD::setModel('App\Models\Monthly');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/monthly');
        CRUD::setEntityNameStrings('mensual', 'mensual');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->addButtonFromView('top', 'download', 'download', 'end');

        $this->crud->operation('list', function(){
           $this->crud->addColumns([
               [
                    'label' => 'Fecha',
                    'name' => 'fecha',
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
        });


        // Filter
        $this->crud->addFilter([
            'name' => 'id_station',
            'type' => 'select2',
            'label' => 'Estación',
        ],function(){

            return Station::all()->pluck('label', 'id')->toArray();

        },function($value){

            $this->crud->addClause('where', 'id_station', $value);

        });

         $this->crud->addFilter([
            'name' => 'year',
            'type' => 'select2_multiple',
            'label' => 'Años',
        ],function(){

           return Yearly::select('fecha')->orderBy('fecha')->pluck('fecha', 'fecha')->toArray();

        },function($values){

            $this->crud->query = $this->crud->query->whereIn('year', json_decode($values));

        });

        $this->crud->addFilter([
            'name' => 'month',
            'type' => 'select2_multiple',
            'label' => 'Meses',
        ],function(){

            return [
                '01' => 'Enero',
                '02' => 'Febrero',
                '03' => 'Marzo',
                '04' => 'Abril',
                '05' => 'Mayo',
                '06' => 'Junio',
                '07' => 'Julio',
                '08' => 'Agosto',
                '09' => 'Septiembre',
                '10' => 'Octubre',
                '11' => 'Noviembre',
                '12' => 'Diciembre'
            ];

        },function($values){

            $this->crud->query = $this->crud->query->whereIn('month', json_decode($values));

        });

          /**
         * Get the SQL definition of the query being run:
         * This includes all the active filters;
         * Save it to the session to pass to the download function.
         * $query = string - escaped SQL statement;
         * $params = array - parameters to insert into the escaped SQL query.
         */


        if($this->crud->actionIs('list') || $this->crud->actionIs('search') ){
            $monthly_query = $this->crud->query->getQuery()->toSql();
            $monthly_params = $this->crud->query->getQuery()->getBindings();
            Session(['monthly_query' => $monthly_query ]);
            Session(['monthly_params' => $monthly_params ]);

        }

    }

    public function download(Request $request)
    {
        $scriptName = 'save_data_csv.py';
        $scriptPath = base_path() . '/scripts/' . $scriptName;
        $base_path = base_path();
        $query = Session('monthly_query');
        $params = join(",",Session('monthly_params'));
        $file_name = date('c')."monthly.csv";
        $query = str_replace('`',' ',$query);

        //python script accepts 4 arguments in this order: base_path(), query, params and file name
        Log::info($query);

        $process = new Process(["pipenv", "run", "python3", $scriptPath, $base_path, $query, $params, $file_name]);

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
