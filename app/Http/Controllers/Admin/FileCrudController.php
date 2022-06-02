<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FileRequest;
use App\Models\Met\Station;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class FileCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class FileCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\File::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/file');
        CRUD::setEntityNameStrings('file', 'files');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('path');
        CRUD::column('name');
        CRUD::column('station_id');
        CRUD::column('created_at');
        CRUD::column('updated_at');
        CRUD::column('is_legacy');
        CRUD::column('is_success');
        CRUD::column('uploader_id');
        CRUD::column('upload_id');
        CRUD::column('observation_id');
        CRUD::column('new_records_count');
        CRUD::column('duplicate_records_count');

        CRUD::filter('station_id')->type('select2')->options(Station::all()->pluck('label','id')->toArray())->whenActive(function($value) {
            $this->crud->addClause('where', 'station_id', $value);
        });

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(FileRequest::class);

        CRUD::field('id');
        CRUD::field('path');
        CRUD::field('name');
        CRUD::field('station_id');
        CRUD::field('created_at');
        CRUD::field('updated_at');
        CRUD::field('is_legacy');
        CRUD::field('is_success');
        CRUD::field('uploader_id');
        CRUD::field('upload_id');
        CRUD::field('observation_id');
        CRUD::field('new_records_count');
        CRUD::field('duplicate_records_count');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
