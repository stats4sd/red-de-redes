<?php

namespace App\Http\Controllers\Admin;

use App\Models\Organisation;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\StationRequest as StoreRequest;
use App\Http\Requests\StationRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;
use Backpack\CRUD\CrudPanel;
use CRUD;

/**
 * Class StationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class StationCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use InlineCreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    use FetchOperation;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Met\Station');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/station');
        $this->crud->setEntityNameStrings('estación', 'estaciones');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->setColumns([
            [
                'name' => 'id',
                'label' => 'ID',
                'type' => 'number',
            ],
            [
                'name' => 'hardware_id',
                'label' => 'ID de hardware',
                'type' => 'text',
            ],
            [
                'name' => 'label',
                'label' => 'Etiqueta',
                'type' => 'text',
            ],
            [
                'name' => 'type',
                'label' => 'Tipo de estación',
                'type' => 'text',
            ],
            [
                'name' => 'organisation',
                'label' => 'Organización',
                'type' => 'relationship',
            ],
            [
                'name' => 'latitude',
                'label' => 'Latitud',
                'type' => 'decimal',
            ],
            [
                'name' => 'longitude',
                'label' => 'Longitud',
                'type' => 'decimal',
            ],
            [
                'name' => 'altitude',
                'label' => 'Altitud',
                'type' => 'decimal',
            ],
            [
                'name' => 'height_wind',
                'label' => 'Altura del instrumento de viento',
                'type' => 'decimal',
            ],
            [
                'name' => 'weatherunderground_url',
                'label' => 'URL Weather Underground ',
                'type' => 'url',
                'wrapper'   => [
                    'href' => function ($crud, $column, $entry) {
                        return $entry->weatherunderground_url;
                    },
                ]
            ],
        ]);

        $this->crud->addFields([
            [
                'name' => 'hardware_id',
                'label' => 'ID de hardware',
                'type' => 'text',
            ],
            [
                'name' => 'label',
                'label' => 'Etiqueta',
                'type' => 'text',
            ],
            [
                'name' => 'organisation_id',
                'label' => 'Organización',
                'type' => 'relationship',
                'ajax' => true,
                'minimum_input_length' => 0,
                'inline_create' => true,
            ],
            [
                'name' => 'type',
                'label' => 'Tipo de estación',
                'type' => 'select2_from_array',
                'options' => ['davis' => 'Davis', 'chinas' => 'Chinas'],
                'allows_null' => false,
                'default' => 'davis',
            ],
            [
                'name' => 'latitude',
                'label' => 'Latitud',
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
            ],
            [
                'name' => 'longitude',
                'label' => 'Longitud',
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
            ],
            [
                'name' => 'altitude',
                'label' => 'Altitud',
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
            ],
            [
                'name' => 'height_wind',
                'label' => 'Altura del instrumento de viento',
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'hint' => 'Medida en metros',
            ],
            [
                'name' => 'weatherunderground_url',
                'label' => 'URL Weather Underground ',
                'type' => 'url',
            ],
        ]);
        // add asterisk for fields that are required in StationRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(StoreRequest::class);

        CRUD::filter('organizaciones')
            ->label('Organización')
            ->type('select2')
            ->options(Organisation::all()->pluck('label','id')->toArray())
            ->whenActive(function($value) {
                $this->crud->addClause('where', 'organisation_id', $value);
            });


    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation(UpdateRequest::class);
    }


    public function fetchOrganisation()
    {
        return $this->fetch([
            'model' => Organisation::class
        ]);
    }

}
