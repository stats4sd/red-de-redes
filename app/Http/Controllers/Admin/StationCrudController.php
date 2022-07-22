<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\StationRequest as StoreRequest;
use App\Http\Requests\StationRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class StationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class StationCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
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

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();
        $this->crud->setColumns([
            [
                'name' => 'id',
                'label' => 'ID',
                'type' => 'number',
            ],
            [
                'name' => 'hardware_id',
                'label' => 'Hardware ID',
                'type' => 'text',
            ],
            [
                'name' => 'label',
                'label' => 'Label',
                'type' => 'text',
            ],
            [
                'name' => 'type',
                'label' => 'Tipo de estación',
                'type' => 'text',
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
        ]);

        $this->crud->addFields([
            [
                'name' => 'hardware_id',
                'label' => 'Hardware ID',
                'type' => 'text',
                'tab' => 'Información básica',
            ],
            [
                'name' => 'label',
                'label' => 'Label',
                'type' => 'text',
                'tab' => 'Información básica',
            ],
            [
                'name' => 'type',
                'label' => 'Tipo de estación',
                'type' => 'select2_from_array',
                'options' => ['davis' => 'Davis', 'chinas' => 'Chinas'],
                'allows_null' => false,
                'default' => 'davis',
                'tab' => 'Información básica',
            ],
            [
                'name' => 'latitude',
                'label' => 'Latitud',
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'tab' => 'Información básica',
            ],
            [
                'name' => 'longitude',
                'label' => 'Longitud',
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'tab' => 'Información básica',
            ],
            [
                'name' => 'altitude',
                'label' => 'Altitud',
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'tab' => 'Información básica',
            ],            
            [
                'name' => 'as', // JSON variable name
                'label' => "as", // human-readable label for the input
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'hint' => 'Fraction of extraterrestrial radiation reaching earth on sunless days',
                'fake' => true, // show the field, but don't store it in the database column above
                'store_in' => 'constants', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
                'tab' => 'Evapotranspiración', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
            ],
            [
                'name' => 'bs', // JSON variable name
                'label' => "bs", // human-readable label for the input
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'hint' => 'Difference between fracion of extraterrestrial radiation reaching full-sun days and that on sunless
                days',
                'fake' => true, // show the field, but don't store it in the database column above
                'store_in' => 'constants', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
                'tab' => 'Evapotranspiración', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
            ],
            [
                'name' => 'z', // JSON variable name
                'label' => "z", // human-readable label for the input
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'hint' => 'Height of wind instrument in metres',
                'fake' => true, // show the field, but don't store it in the database column above
                'store_in' => 'constants', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
                'tab' => 'Evapotranspiración', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
            ],
            [
                'name' => 'fz', // JSON variable name
                'label' => "fz", // human-readable label for the input
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'hint' => 'Constant in Morton’s procedure',
                'fake' => true, // show the field, but don't store it in the database column above
                'store_in' => 'constants', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
                'tab' => 'Evapotranspiración', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
            ],
            [
                'name' => 'a_0', // JSON variable name
                'label' => "a_0", // human-readable label for the input
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'hint' => 'Constant for estimating sunshine hours from cloud cover data',
                'fake' => true, // show the field, but don't store it in the database column above
                'store_in' => 'constants', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
                'tab' => 'Evapotranspiración', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
            ],
            [
                'name' => 'b_0', // JSON variable name
                'label' => "b_0", // human-readable label for the input
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'hint' => 'Constant for estimating sunshine hours from cloud cover data',
                'fake' => true, // show the field, but don't store it in the database column above
                'store_in' => 'constants', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
                'tab' => 'Evapotranspiración', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
            ],
            [
                'name' => 'c_0', // JSON variable name
                'label' => "c_0", // human-readable label for the input
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'hint' => 'Constant for estimating sunshine hours from cloud cover data',
                'fake' => true, // show the field, but don't store it in the database column above
                'store_in' => 'constants', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
                'tab' => 'Evapotranspiración', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
            ],
            [
                'name' => 'd_0', // JSON variable name
                'label' => "d_0", // human-readable label for the input
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'hint' => 'Constant for estimating sunshine hours from cloud cover data',
                'fake' => true, // show the field, but don't store it in the database column above
                'store_in' => 'constants', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
                'tab' => 'Evapotranspiración', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
            ],
            [
                'name' => 'gammaps', // JSON variable name
                'label' => "gammaps", // human-readable label for the input
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'hint' => 'Product of Psychrometric constant and atmospheric pressure as sea level',
                'fake' => true, // show the field, but don't store it in the database column above
                'store_in' => 'constants', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
                'tab' => 'Evapotranspiración', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
            ],
            [
                'name' => 'PA', // JSON variable name
                'label' => "PA", // human-readable label for the input
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'hint' => 'Annual precipitation',
                'fake' => true, // show the field, but don't store it in the database column above
                'store_in' => 'constants', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
                'tab' => 'Evapotranspiración', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
            ],
            [
                'name' => 'alphaMo', // JSON variable name
                'label' => "alphaMo", // human-readable label for the input
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'hint' => 'Constant in Morton’s procedure',
                'fake' => true, // show the field, but don't store it in the database column above
                'store_in' => 'constants', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
                'tab' => 'Evapotranspiración', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
            ],
            [
                'name' => 'betaMo', // JSON variable name
                'label' => "betaMo", // human-readable label for the input
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'hint' => 'Constant in Morton’s procedure',
                'fake' => true, // show the field, but don't store it in the database column above
                'store_in' => 'constants', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
                'tab' => 'Evapotranspiración', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
            ],
            [
                'name' => 'lambdaMo', // JSON variable name
                'label' => "lambdaMo", // human-readable label for the input
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'hint' => 'Latent heat of vaporisation in Morton’s procedure',
                'fake' => true, // show the field, but don't store it in the database column above
                'store_in' => 'constants', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
                'tab' => 'Evapotranspiración', // [optional] the database column name where you want the fake fields to ACTUALLY be stored as a JSON array 
            ],
        ]);
        // add asterisk for fields that are required in StationRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
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
