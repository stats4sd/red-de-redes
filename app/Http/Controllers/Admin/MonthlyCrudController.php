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
        CRUD::setEntityNameStrings('monthly', 'monthly');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->addButtonFromView('top', 'download', 'download', 'end');

        $this->crud->operation('list', function(){
            $this->crud->addColumn('year')->makeFirstColumn();
            $this->crud->addColumn('month')->afterColumn('year');
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

        $this->crud->enableExportButtons();

        // Filter
        $this->crud->addFilter([
            'name' => 'id_station',
            'type' => 'select2',
            'label' => 'Station',
        ],function(){
           
            return Station::all()->pluck('label', 'id')->toArray();

        },function($value){
            $this->crud->addClause('where', 'id_station', $value);

        });

         $this->crud->addFilter([
            'name' => 'year',
            'type' => 'select2_multiple',
            'label' => 'Years',
        ],function(){

           return Yearly::select('fecha')->orderBy('fecha')->pluck('fecha', 'fecha')->toArray();

        },function($values){

           foreach(json_decode($values) as $key => $value) {
            
               $this->crud->addClause('where', 'year', $value);
            }

        });

        $this->crud->addFilter([
            'name' => 'month',
            'type' => 'select2_multiple',
            'label' => 'Months',
        ],function(){
           
            return [
                '01' => 'January',
                '02' => 'February',
                '03' => 'March',
                '04' => 'April',
                '05' => 'May',
                '06' => 'June',
                '07' => 'July',
                '08' => 'August',
                '09' => 'September',
                '10' => 'October',
                '11' => 'November',
                '12' => 'December'
            ];

        },function($values){

           foreach(json_decode($values) as $key => $value) {

               $this->crud->addClause('Where', 'month', $value);
            }

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
        $query = '"'.$query.'"';
        $params = '"'.$params.'"';
        $file_name = date('c')."monthly.csv";
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
