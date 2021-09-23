<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\YearlyRequest as StoreRequest;
use App\Http\Requests\YearlyRequest as UpdateRequest;
use App\Jobs\ProcessDataExport;
use App\Models\Station;
use App\Models\Yearly;
use Backpack\CRUD\CrudPanel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Class YearlyCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class YearlyCrudController extends CrudController
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
        CRUD::setModel('App\Models\Yearly');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/yearly');
        CRUD::setEntityNameStrings('anual', 'anual');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->operation('list', function(){
           $this->crud->addColumns([
                [
                    'label' => 'Fecha',
                    'name' => 'fecha',
                    'type' => 'year',
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

        // add asterisk for fields that are required in YearlyRequest

        $this->crud->addButtonFromView('top', 'download', 'download', 'end');

        // Filter
        $this->crud->addFilter([
            'name' => 'id_station',
            'type' => 'select2',
            'label' => 'Estación',
        ],function(){

            return Station::all()->pluck('label', 'id')->toArray();;

        },function($value){
            $this->crud->addClause('where', 'id_station', $value);

        });

        $this->crud->addFilter([
            'name' => 'fecha',
            'type' => 'select2_multiple',
            'label' => 'Año',
        ],function(){
           $years = Yearly::select('fecha')->orderBy('fecha')->pluck('fecha', 'fecha')->toArray();
            return $years;

        },function($values){

           $this->crud->query = $this->crud->query->whereIn('fecha', json_decode($values));

        });

        if($this->crud->actionIs('list') || $this->crud->actionIs('search') ){
            $yearly_query = $this->crud->query->getQuery()->toSql();
            $yearly_params = $this->crud->query->getQuery()->getBindings();
            Session(['yearly_query' => $yearly_query ]);
            Session(['yearly_params' => $yearly_params ]);

        }

    }

    public function download(Request $request)
    {
        $scriptName = 'save_data_csv.py';
        $scriptPath = base_path() . '/scripts/' . $scriptName;
        $base_path = base_path();
        $query = Session('yearly_query');
        $params = join(",",Session('yearly_params'));
        $file_name = date('c')."yearly.csv";
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
