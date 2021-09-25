<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MunicipioRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

/**
 * Class MunicipioCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MunicipioCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Municipio');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/municipio');
        $this->crud->setEntityNameStrings('municipio', 'municipios');
    }

    protected function setupListOperation()
    {
        // TODO: remove setFromDb() and manually define Columns, maybe Filters
        $this->crud->setColumns([
            [// 1-n relationship
               'label'     => 'Departamento', // Table column heading
               'type'      => 'select',
               'name'      => 'departamento_id', // the column that contains the ID of that connected entity;
               'entity'    => 'departamento', // the method that defines the relationship in your Model
               'attribute' => 'name', // foreign key attribute that is shown to user
               'model'     => "App\Models\Departamento", // foreign key model
            ]
        ]);
        $this->crud->setFromDb();
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(MunicipioRequest::class);

        // TODO: remove setFromDb() and manually define Fields
        $this->crud->addFields([
            [  // Select2
               'label'     => "Departamento",
               'type'      => 'select2',
               'name'      => 'departamento_id', // the db column for the foreign key
               // optional
               'entity'    => 'departamento', // the method that defines the relationship in your Model
               'model'     => "App\Models\Departamento", // foreign key model
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
