<?php

namespace App\Http\Controllers\Admin;

use App\Models\Met\Station;
use App\Http\Requests\FileRequest;
use App\Models\Organisation;
use Illuminate\Support\Facades\Storage;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Controllers\Admin\Operations\MarkToRemoveOperation;
use App\Http\Controllers\Admin\Operations\MarkToKeepOperation;

class FileCrudController extends CrudController
{

    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use MarkToRemoveOperation;
    use MarkToKeepOperation;

    public function setup()
    {
        $this->crud->setModel("App\Models\Met\File");
        $this->crud->setRoute("admin/file");
        $this->crud->setEntityNameStrings('archivo', 'archivos');

    }

    protected function setupListOperation()
    {

        $this->crud->addColumns([
            // Question: is it possible to add a column but not showing it in CRUD panel?
            [
                'name' => 'id',
                'type' => 'hidden',
                'label' => 'ID',
                'visibleInTable' => false,
            ],
            [
                'name' => 'original_name',
                'type' => 'text',
                'label' => 'Nombre original',
                'wrapper' => [
                    'href' => function ($crud, $column, $entry) {
                        return Storage::url($entry->path);
                    },
                ]
            ],
            [
                'name' => 'station.label',
                'type' => 'text',
                'label' => 'Estación',
            ],
            [
                'name' => 'station_id',
                'type' => 'text',
                'label' => 'ID de estación',
            ],
            [
                'name' => 'uploader.name',
                'type' => 'text',
                'label' => 'Subido por',
            ],
            [
                'name' => 'created_at',
                'type' => 'date',
                'label' => 'Fecha de subida',
            ],
            [
                'name' => 'upload_id',
                'type' => 'text',
                'label' => 'ID del subido',
            ],
            // [
            //     'name'  => 'observation_id',
            //     'type'  => 'text',
            //     'label' => 'ID de observación',
            // ],
            [
                'name' => 'new_records_count',
                'type' => 'text',
                'label' => 'Número de registros nuevos',
            ],
            [
                'name' => 'duplicate_records_count',
                'type' => 'text',
                'label' => 'Número de registros duplicados',
            ],
            [
                'name' => 'is_success',
                'type' => 'boolean',
                'label' => 'Subido con éxito',
            ],
            [
                'name' => 'is_marked_to_keep',
                'type' => 'boolean',
                'label' => 'Marked to Keep',
            ],
            [
                'name' => 'is_marked_to_remove',
                'type' => 'boolean',
                'label' => 'Marked to Remove',
            ],
        ]);

        $this->crud->addFilter([
            'name' => 'organisation',
            'label' => 'Organizacion',
            'type' => 'dropdown',
        ], function () {
            return Organisation::all()->pluck('label', 'id')->toArray();
        }, function ($value) {
            $this->crud->query = $this->crud->query->whereHas('station', function($query) use ($value) {
                $query->where('organisation_id', $value);
            });
        });

        $this->crud->addFilter([
            'name' => 'station_id',
            'type' => 'dropdown',
            'label' => 'Estación'
        ], function () {
            return Station::all()->pluck('label', 'id')->toArray();
        }, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'station_id', $value);
        });

        $this->crud->addFilter([
            'name' => 'is_success',
            'type' => 'dropdown',
            'label' => 'Subido con éxito'
        ], function () {
            return [
                0 => 'No',
                1 => 'Sí',
            ];
        }, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'is_success', $value);
        });

        $this->crud->addFilter([
            'name' => 'is_marked_to_keep',
            'type' => 'dropdown',
            'label' => 'Marked to keep'
        ], function () {
            return [
                0 => 'No',
                1 => 'Sí',
            ];
        }, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'is_marked_to_keep', $value);
        });

        $this->crud->addFilter([
            'name' => 'is_marked_to_remove',
            'type' => 'dropdown',
            'label' => 'Marked to remove'
        ], function () {
            return [
                0 => 'No',
                1 => 'Sí',
            ];
        }, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'is_marked_to_remove', $value);
        });


    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }

}
