<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;

trait MarkAsReviewedOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupMarkAsReviewedRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/markasreviewed', [
            'as'        => $routeName.'.markasreviewed',
            'uses'      => $controller.'@markasreviewed',
            'operation' => 'markasreviewed',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupMarkAsReviewedDefaults()
    {
        $this->crud->allowAccess('markasreviewed');

        $this->crud->operation('markasreviewed', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
             $this->crud->addButton('line', 'markasreviewed', 'view', 'crud::buttons.markAsReviewed', 'beginning');
        });
    }


    public function markasreviewed($id) 
    {
        $this->crud->model->findOrFail($id)->update(array('is_marked_as_reviewed' => 1));
    }
}
