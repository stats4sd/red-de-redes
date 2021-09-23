<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\UserRequest as StoreRequest;
use App\Http\Requests\UserRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Support\Facades\Hash;


/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\User');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/user');
        $this->crud->setEntityNameStrings('usuario', 'usuarios');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->operation('list', function() {
            $this->crud->setColumns([
                [
                    'name' => 'name',
                    'label' => 'Nombre',
                    'type' => 'text',
                ],
                [
                    'name' => 'email',
                    'label' => 'Email',
                    'type' => 'email',
                ],
                [
                    'name' => 'type',
                    'label' => 'Tipo',
                    'type' => 'text',
                ],
                [
                    'name' => 'created_at',
                    'label' => 'Created at',
                    'type' => 'date',
                ],
            ]);
       
        });

        $this->crud->operation(['create'], function() {

            $this->crud->addFields([
                [
                    'name' => 'name',
                    'label' => 'Nombre',
                    'type' => 'text',
                    'priority' => 1,
                ],
                [
                    'name' => 'password',
                    'label' => 'Contraseña',
                    'type' => 'password',
                    'priority' => 1,
                ],
                [
                    'name' => 'email',
                    'label' => 'Email',
                    'type' => 'email',
                ],
                [
                    'name' => 'type',
                    'label' => 'Tipo',
                    'type' => 'select_from_array',
                    'options' => ['default' => 'Default', 'admin' => 'Admin'],        
                ],
            ]);
     
        });

        $this->crud->operation(['update'], function() {

            $this->crud->addFields([
                [
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'text',
                    'priority' => 1,
                ],
                [
                    'name' => 'email',
                    'label' => 'Email',
                    'type' => 'email',
                ],
                [
                    'name' => 'type',
                    'label' => 'Type',
                    'type' => 'select_from_array',
                    'options' => ['default' => 'Default', 'admin' => 'Admin'],        
                ],
            ]);
     
        });


        // add asterisk for fields that are required in UserRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->removeButton('create');

       
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(StoreRequest::class);
    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation(UpdateRequest::class);
    }

   


}
