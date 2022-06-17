<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;

trait MarkToKeepOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupMarkToKeepRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/marktokeep', [
            'as'        => $routeName.'.marktokeep',
            'uses'      => $controller.'@marktokeep',
            'operation' => 'marktokeep',
        ]);

        Route::post($segment.'/{id}/unmarktokeep', [
            'as'        => $routeName.'.unmarktokeep',
            'uses'      => $controller.'@unmarktokeep',
            'operation' => 'unmarktokeep',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupMarkToKeepDefaults()
    {
        $this->crud->allowAccess('marktokeep');

        $this->crud->operation('marktokeep', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
             $this->crud->addButton('line', 'marktokeep', 'view', 'crud::buttons.markToKeep', 'beginning');
        });
    }


    public function marktokeep($id) 
    {
        $this->crud->model->findOrFail($id)->update(array('is_marked_to_keep' => 1));
    }


    public function unmarktokeep($id) 
    {
        $this->crud->model->findOrFail($id)->update(array('is_marked_to_keep' => 0));
    }

}
