<?php namespace App\Http\Controllers\Admin;

use App\Models\Met\Station;
use App\Http\Requests\FileRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class FileCrudController extends CrudController {

  use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

  public function setup() 
  {
      $this->crud->setModel("App\Models\Met\File");
      $this->crud->setRoute("admin/file");
      $this->crud->setEntityNameStrings('archivo', 'archivos');
  
  }

  protected function setupListOperation()
  {

    $this->crud->addColumns([
        [
            'name'  => 'original_name',
            'type'  => 'text',
            'label' => 'Nombre original',
        ],
        [
            'name'  => 'station.label',
            'type'  => 'text',
            'label' => 'Estación',
        ],
        [
            'name'  => 'station_id',
            'type'  => 'text',
            'label' => 'ID de estación',
        ],
        [
            'name'  => 'is_success',
            'type'  => 'check',
            'label' => 'Subido con éxito',
        ],
        [
            'name'  => 'is_legacy',
            'type'  => 'check',
            'label' => 'Archivo heredado',
        ],
        [
            'name'  => 'uploader_id',
            'type'  => 'text',
            'label' => 'ID del subidor',
        ],
        [
            'name'  => 'upload_id',
            'type'  => 'text',
            'label' => 'ID del subido',
        ],
        [
            'name'  => 'observation_id',
            'type'  => 'text',
            'label' => 'ID de observación',
        ],
        [
            'name'  => 'new_records_count',
            'type'  => 'text',
            'label' => 'Número de registros nuevos',
        ],
        [
            'name'  => 'duplicate_records_count',
            'type'  => 'text',
            'label' => 'Número de registros duplicados',
        ],
        [
            'name'  => 'name',
            'type'  => 'text',
            'label' => 'Nombre',
        ],
        [
            'name'  => 'path',
            'type'  => 'text',
            'label' => 'Path',
        ],
    ]);

    $this->crud->addFilter([
        'name'  => 'station_id',
        'type'  => 'dropdown',
        'label' => 'Estación'
    ], function () {
        $stations = Station::all()->pluck('label', 'id')->toArray();

        return $stations;
    }, function($value) { // if the filter is active
        $this->crud->addClause('where', 'station_id', $value);
      });

      $this->crud->addFilter([
        'name'  => 'is_success',
        'type'  => 'dropdown',
        'label' => 'Subido con éxito'
    ], function() {
        return [
            0 => 'No',
            1 => 'Sí',
          ];
    }, function($value) { // if the filter is active
        $this->crud->addClause('where', 'is_success', $value);
      });
      
  }

  protected function setupShowOperation()
  {
      $this->setupListOperation();
  }

}