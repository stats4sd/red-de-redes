<?php

namespace App\Http\Controllers\Admin;

use App\Models\Station;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\WeatherDataRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class WeatherDataCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class WeatherDataCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\WeatherData::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/weather_data');
        CRUD::setEntityNameStrings('datos meteorológicos', 'datos meteorológicos');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setColumns([
            [
                'name' => 'fecha',
                'label' => 'Date',
                'type' => 'date',
                'format' => 'MM-DD-YYYY',
            ],
            [
                'name' => 'hora',
                'label' => 'Time',
                'type' => 'time',
              
            ],
            [
                'name' => 'temperatura_externa',
                'label' => 'Temp Out',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'hi_temp',
                'label' => 'Hi Temp',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'low_temp',
                'label' => 'Low Temp',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'humedad_externa',
                'label' => 'Out Hum',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'punto_rocio',
                'label' => 'Dew Pt.',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'velocidad_viento',
                'label' => 'Wind Speed',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'direccion_del_viento',
                'label' => 'Wind Dir',
                'type' => 'text',
            ],
            [
                'name' => 'wind_run',
                'label' => 'Wind Run',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'hi_speed',
                'label' => 'Hi Speed',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'hi_dir',
                'label' => 'Hi Dir',
                'type' => 'text',
            ],
            [
                'name' => 'wind_chill',
                'label' => 'Wind Chill',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'index_heat',
                'label' => 'Heat Index',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'index_thw',
                'label' => 'THW Index',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'index_thsw',
                'label' => 'THSW Index',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'presion_relativa',
                'label' => 'Bar',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'rain',
                'label' => 'Rain',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'lluvia_hora',
                'label' => 'Rain Rate',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'solar_rad',
                'label' => 'Solar Rad.',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'solar_energy',
                'label' => 'Solar Energy',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'radsolar_max',
                'label' => 'Hi Solar Rad.',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'uv_index',
                'label' => 'UV Index',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'uv_dose',
                'label' => 'UV Dose',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'uv_max',
                'label' => 'Hi UV',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'heat_days_d',
                'label' => 'Heat D-D',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'cool_days_d',
                'label' => 'Cool_D-D',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'temperatura_interna',
                'label' => 'In Temp',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'humedad_interna',
                'label' => 'In Hum',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'in_dew',
                'label' => 'In Dew',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'in_heat',
                'label' => 'In Heat',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'in_emc',
                'label' => 'In EMC',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'in_air_density',
                'label' => 'In Air Density',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'evapotran',
                'label' => 'ET',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'soil_1_moist',
                'label' => 'Soil 1 Moist.',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'soil_2_moist',
                'label' => 'Soil 2 Moist.',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'soil_temp_1',
                'label' => 'Soil Temp 1.',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'soil_temp_2',
                'label' => 'Soil Temp 2.',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'soil_temp_3',
                'label' => 'Soil_Temp_3.',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'leaf_wet1',
                'label' => 'Leaf Wet 1',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'leaf_wet2',
                'label' => 'Leaf Wet 2',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'leaf_wet3',
                'label' => 'Leaf Wet 3',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'wind_samp',
                'label' => 'Wind Samp',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'wind_tx',
                'label' => 'Wind Tx',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'iss_recept',
                'label' => 'ISS Recept',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'intervalo',
                'label' => 'Arc. Int.',
                'type' => 'number',
                'decimals'=> 2,
            ],
            [
                'name' => 'meteobridge',
                'label' => 'Meteobridge',
                'type' => 'boolean',
            ],



        ]);
        // CRUD::setFromDb(); // columns

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */

        $this->crud->addFilter([
            'name' => 'station',
            'type' => 'select2',
            'label' => 'Estación',
        ], function () {
            return Station::all()->pluck('label', 'label')->toArray();
        }, function ($value) {
            $this->crud->addClause('where', 'station', $value);
        });

        $this->crud->addFilter(
            [ // daterange filter
                'type' => 'date_range',
                'name' => 'from_to',
                'label'=> 'Rango de fechas'

            ],
            false,
            function ($value) { // if the filter is active, apply these constraints
            $dates = json_decode($value);
            $this->crud->addClause('where', 'fecha', '>=', $dates->from);
            $this->crud->addClause('where', 'fecha', '<=', $dates->to . ' 23:59:59');
        });

        $this->crud->addFilter([ 
            'type'  => 'simple',
            'name'  => 'meteobridge',
            'label' => 'Meteobridge'
          ],
          false, // the simple filter has no values, just the "Draft" label specified above
          function() { // if the filter is active (the GET parameter "draft" exits)
            $this->crud->addClause('where', 'meteobridge', '1');
        }); 

        $this->crud->addButtonFromView('top', 'download', 'download', 'end');
        $this->crud->setDefaultPageLength(50);

        /**
         * Get the SQL definition of the query being run:
         * This includes all the active filters;
         * Save it to the session to pass to the download function.
         * $query = string - escaped SQL statement;
         * $params = array - parameters to insert into the escaped SQL query.
         */


        if ($this->crud->actionIs('list') || $this->crud->actionIs('search')) {
            $query = $this->crud->query->getQuery()->toSql();
            $params = $this->crud->query->getQuery()->getBindings();
            Session(['query' => $query ]);
            Session(['params' => $params ]);
        }
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(WeatherDataRequest::class);

        CRUD::setFromDb(); // fields

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

    public function download()
    {
        $scriptName = 'save_data_csv.py';
        $scriptPath = base_path() . '/scripts/' . $scriptName;
        $base_path = base_path();
        $query = Session('query');
        $params = join(",", Session('params'));
        $date = str_replace(':', '', date('c'));
        $date = str_replace('-', '', $date);
        $date = str_replace('+', '', $date);
        $file_name = date('c')."data.csv";
        $query = str_replace('`', ' ', $query);

        //python script accepts 4 arguments in this order: base_path(), query, params and file name
        Log::info($query);

        $process = new Process(['pipenv', 'run', 'python3', $scriptPath, $base_path, $query, $params, $file_name]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        } else {
            $process->getOutput();
        }
        Log::info("python done.");
        Log::info($process->getOutput());

        $path_download =  Storage::url('/data/'.$file_name);
        return response()->json(['path' => $path_download]);
    }
}
