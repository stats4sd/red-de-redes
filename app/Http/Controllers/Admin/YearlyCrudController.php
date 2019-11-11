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
        CRUD::setEntityNameStrings('yearly', 'yearly');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->operation('list', function(){
            $this->crud->addColumn('fecha')->makeFirstColumn();
            $this->crud->addColumn([

                'label' => 'Station',
                'type' => 'select',
                'name' => 'id_station',
                'entity' => 'station',
                'attribute' => 'label',
                'model' => 'App\Models\Station',
                'key' => 'updated_at'

            ])->afterColumn('fecha');
            $this->crud->setFromDb();
        });

        // add asterisk for fields that are required in YearlyRequest
       
        $this->crud->enableExportButtons();
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

        $this->crud->addFilter([
            'name' => 'fecha',
            'type' => 'select2_multiple',
            'label' => 'Year',
        ],function(){
           $years = Yearly::select('fecha')->orderBy('fecha')->pluck('fecha', 'fecha')->toArray();         
            return $years;

        },function($values){

           foreach(json_decode($values) as $key => $value) {

               $this->crud->addClause('OrWhere', 'fecha', $value);
            }

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
        $query = '"'.$query.'"';
        $params = '"'.$params.'"';
        $file_name = '"'.date('c')."yearly.csv".'"';
        $query = str_replace('`',' ',$query);

        //python script accepts 4 arguments in this order: base_path(), query, params and file name
        Log::info($query);
      
        $process = new Process("python3.7 {$scriptPath} {$base_path} {$query} {$params} {$file_name}");

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
