<?php

namespace App\Http\Controllers\Admin\Met;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Exports\Download\DownloadWorkbookExport;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\StationRequest as StoreRequest;
use App\Http\Requests\StationRequest as UpdateRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\CrudPanel;
use App\Http\Controllers\Operations\ExportOperation;

/**
 * Class StationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MetRawDataCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    use ExportOperation;
    
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Met\MetData');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/met_raw_data');
        $this->crud->setEntityNameStrings('met_raw_data', 'met_raw_datas');

        CRUD::set('export.exporter', DownloadWorkbookExport::class);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();
        $this->crud->setColumns([
            [
                'name' => 'id',
                'label' => 'ID',
                'type' => 'number',
            ],
            [
                'name' => 'intervalo',
                'label' => 'Intervalo',
                'type' => 'decimal',
            ],
            [
                'name' => 'temperature_interna',
                'label' => 'Temperature Interna',
                'type' => 'decimal',
            ],

        ]);

        /*
        $this->crud->addFields([
            [
                'name' => 'hardware_id',
                'label' => 'Hardware ID',
                'type' => 'text',
            ],
            [
                'name' => 'label',
                'label' => 'Label',
                'type' => 'text',
            ],
            
        ]);
        */

        // add asterisk for fields that are required in StationRequest
        //$this->crud->setRequiredFields(StoreRequest::class, 'create');
        //$this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    /*
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(StoreRequest::class);
    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation(UpdateRequest::class);
    }
    */

}
