<?php

namespace App\Exports\Download\Met;

use Illuminate\Http\Request;
use App\Models\Met\MetData;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class MetDataExport implements FromQuery, WithTitle, WithHeadings, WithStrictNullComparison, WithMapping
{

    // HTTP request
    protected $request;

    // fields to be extracted
    protected $fields = [];

    // constructor to set HTTP request object to private variable
    public function __construct(Request $request = null)
    {
        logger("MetDataExport.construct() starts...");

        $this->request = $request;

        $this->fields = [
            'station_id',
            'fecha_hora',
            //'id',
            'intervalo',
            'temperatura_interna',
            'humedad_interna',
            'temperatura_externa',
            'humedad_externa',
            'presion_relativa',
            'presion_absoluta',
            'velocidad_viento',
            'sensacion_termica',
            'rafaga',
            'direccion_del_viento',
            'punto_rocio',
            'lluvia_hora',
            'lluvia_24_horas',
            'lluvia_semana',
            'lluvia_mes',
            'lluvia_total',
            'hi_temp',
            'low_temp',
            'wind_cod',
            'wind_run',
            'hi_speed',
            'hi_dir',
            'wind_cod_dom',
            'wind_chill',
            'index_heat',
            'index_thw',
            'index_thsw',
            'rain',
            'solar_rad',
            'solar_energy',
            'radsolar_max',
            'uv_index',
            'uv_dose',
            'uv_max',
            'heat_days_d',
            'cool_days_d',
            'in_dew',
            'in_heat',
            'in_emc',
            'in_air_density',
            'evapotran',
            'soil_1_moist',
            'soil_2_moist',
            'leaf_wet1',
            'leaf_wet2',
            'leaf_wet3',
            'leaf_wet4',
            'wind_samp',
            'wind_tx',
            'iss_recept',            
            'observation_id',
            'meteobridge_latitude',
            'meteobridge_longitude',
            'meteobridge_altitude',
            'leaf_temp_1',
            'leaf_temp_2',
            'leaf_temp_3',
            'leaf_temp_4',
            'soil_temp_1',
            'soil_temp_2',
            'soil_temp_3',
            'soil_temp_4',
            'soil_3_moist',
            'soil_4_moist',
            'meteobridge',
            'hardware_id',
            'created_at',
            'updated_at',

        ];
    }

    /**
    * By using WithMapping, maps the data that needs to be added as a row.
    * That means you can "construct" the data for a row
    */
    public function map($metData): array
    {
        return [
            $metData->station_id,
            $metData->fecha_hora,
            //$metData->id,
            $metData->intervalo,
            $metData->temperatura_interna,
            $metData->humedad_interna,
            $metData->temperatura_externa,
            $metData->humedad_externa,
            $metData->presion_relativa,
            $metData->presion_absoluta,
            $metData->velocidad_viento,
            $metData->sensacion_termica,
            $metData->rafaga,
            $metData->direccion_del_viento,
            $metData->punto_rocio,
            $metData->lluvia_hora,
            $metData->lluvia_24_horas,
            $metData->lluvia_semana,
            $metData->lluvia_mes,
            $metData->lluvia_total,
            $metData->hi_temp,
            $metData->low_temp,
            $metData->wind_cod,
            $metData->wind_run,
            $metData->hi_speed,
            $metData->hi_dir,
            $metData->wind_cod_dom,
            $metData->wind_chill,
            $metData->index_heat,
            $metData->index_thw,
            $metData->index_thsw,
            $metData->rain,
            $metData->solar_rad,
            $metData->solar_energy,
            $metData->radsolar_max,
            $metData->uv_index,
            $metData->uv_dose,
            $metData->uv_max,
            $metData->heat_days_d,
            $metData->cool_days_d,
            $metData->in_dew,
            $metData->in_heat,
            $metData->in_emc,
            $metData->in_air_density,
            $metData->evapotran,
            $metData->soil_1_moist,
            $metData->soil_2_moist,
            $metData->leaf_wet1,
            $metData->leaf_wet2,
            $metData->leaf_wet3,
            $metData->leaf_wet4,
            $metData->wind_samp,
            $metData->wind_tx,
            $metData->iss_recept,            
            $metData->observation_id,
            $metData->meteobridge_latitude,
            $metData->meteobridge_longitude,
            $metData->meteobridge_altitude,
            $metData->leaf_temp_1,
            $metData->leaf_temp_2,
            $metData->leaf_temp_3,
            $metData->leaf_temp_4,
            $metData->soil_temp_1,
            $metData->soil_temp_2,
            $metData->soil_temp_3,
            $metData->soil_temp_4,
            $metData->soil_3_moist,
            $metData->soil_4_moist,
            $metData->meteobridge,
            $metData->hardware_id,
            $metData->created_at,
            $metData->updated_at,

            // add additional field here
            //$metData->survey->urban,
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        // get station Ids, from month, from year, to month, to year from request
        // Vue component should have validated all of them, each of them should have value
        $stationIds = $this->request->query('stations');
        $fromMonth = $this->request->query('fromMonth');
        $fromYear = $this->request->query('fromYear');
        $toMonth = $this->request->query('toMonth');
        $toYear = $this->request->query('toYear');

        // convert station Ids from CSV format to an array
        $stationArray = str_getcsv($stationIds);

        // prepare from date as first day of From Year, Month
        $strFromDate = $fromYear . '-' . $fromMonth . '-01 00:00:00';

        // prepare to date as first day of next month of To Year, Month
        $tempToDate = $toYear . '-' . $toMonth . '-01';
        $toDate = strtotime($tempToDate);
        $strToDate = date("Y-m-d", strtotime("+1 month", $toDate)) . ' 00:00:00';

        // whereIn for station Ids
        // whereBetween used for From Date and To Date, date time is inclusive
        // records are order by station Id and date time
        $query = MetData::select($this->fields)
                 ->whereIn('station_id', $stationArray)
                 ->whereBetween('fecha_hora', [$strFromDate, $strToDate])
                 ->orderBy('station_id')
                 ->orderBy('fecha_hora');

        return $query;
    }

    public function title(): string
    {
        return 'Met Raw Data';
    }

    public function headings(): array
    {
        // add extra column urban
        $headers = $this->fields;
        
        // TODO: add additional header here
        //array_push($headers, 'urban');

        return $headers;
    }

}