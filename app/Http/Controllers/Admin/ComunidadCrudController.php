<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ComunidadRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ComunidadCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ComunidadCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Comunidad');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/comunidad');
        $this->crud->setEntityNameStrings('comunidad', 'comunidades');
    }

    protected function setupListOperation()
    {
        // TODO: remove setFromDb() and manually define Columns, maybe Filters
        $this->crud->setColumns([

            [// 1-n relationship
               'label'     => 'Municipio', // Table column heading
               'type'      => 'select',
               'name'      => 'municipio_id', // the column that contains the ID of that connected entity;
               'entity'    => 'municipio', // the method that defines the relationship in your Model
               'attribute' => 'name', // foreign key attribute that is shown to user
               'model'     => "App\Models\Municipio", // foreign key model
            ]
        ]);

        $this->crud->setFromDb();
           
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ComunidadRequest::class);

        // TODO: remove setFromDb() and manually define Fields
        $this->crud->addFields([
            [  // Select2
               'label'     => "Municipio",
               'type'      => 'select2',
               'name'      => 'municipio_id', // the db column for the foreign key
               // optional
               'entity'    => 'municipio', // the method that defines the relationship in your Model
               'model'     => "App\Models\Municipio", // foreign key model
               'attribute' => 'name', // foreign key attribute that is shown to user
            ]

        ]);
        $this->crud->setFromDb();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
