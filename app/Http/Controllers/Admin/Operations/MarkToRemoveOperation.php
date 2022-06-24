<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;

trait MarkToRemoveOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupMarkToRemoveRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/marktoremove', [
            'as'        => $routeName.'.marktoremove',
            'uses'      => $controller.'@marktoremove',
            'operation' => 'marktoremove',
        ]);

        Route::post($segment.'/{id}/unmarktoremove', [
            'as'        => $routeName.'.unmarktoremove',
            'uses'      => $controller.'@unmarktoremove',
            'operation' => 'unmarktoremove',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupMarkToRemoveDefaults()
    {
        $this->crud->allowAccess('marktoremove');

        $this->crud->operation('marktoremove', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
             $this->crud->addButton('line', 'marktoremove', 'view', 'crud::buttons.markToRemove', 'beginning');
        });
    }


    public function marktoremove($id) 
    {
        $this->crud->model->findOrFail($id)->update(array('is_marked_to_remove' => 1));
    }


    public function unmarktoremove($id) 
    {
        $this->crud->model->findOrFail($id)->update(array('is_marked_to_remove' => 0));
    }

}
