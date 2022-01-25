<?php

namespace App\Exports\Download\Met;

use Illuminate\Http\Request;
use App\Models\Met\YearlyMetData;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class YearlyMetDataExport implements FromQuery, WithTitle, WithHeadings, WithStrictNullComparison, WithMapping
{

    // HTTP request
    protected $request;

    // fields to be extracted
    protected $fields = [];

    // constructor to set HTTP request object to private variable
    public function __construct(Request $request = null)
    {
        logger("YearlyMetDataExport.construct() starts...");

        $this->request = $request;

        $this->fields = [
            'station_id',
            'year',
            //'id',
            'max_temperatura_interna',
            'min_temperatura_interna',
            'avg_temperatura_interna',
            'max_humedad_interna',
            'min_humedad_interna',
            'avg_humedad_interna',
            'max_temperatura_externa',
            'min_temperatura_externa',
            'avg_temperatura_externa',
            'max_humedad_externa',
            'min_humedad_externa',
            'avg_humedad_externa',
            'max_presion_relativa',
            'min_presion_relativa',
            'avg_presion_relativa',
            'max_presion_absoluta',
            'min_presion_absoluta',
            'avg_presion_absoluta',
            'max_velocidad_viento',
            'min_velocidad_viento',
            'avg_velocidad_viento',
            'max_sensacion_termica',
            'min_sensacion_termica',
            'avg_sensacion_termica',
            'lluvia_24_horas_total',
            'actual_no_of_records',
            'expected_no_of_records',
            'created_at',
            'updated_at',

        ];
    }

    /**
    * By using WithMapping, maps the data that needs to be added as a row.
    * That means you can "construct" the data for a row
    */
    public function map($yearlyMetData): array
    {
        return [
            $yearlyMetData->station_id,
            $yearlyMetData->year,
            //$yearlyMetData->id,
            $yearlyMetData->max_temperatura_interna,
            $yearlyMetData->min_temperatura_interna,
            $yearlyMetData->avg_temperatura_interna,
            $yearlyMetData->max_humedad_interna,
            $yearlyMetData->min_humedad_interna,
            $yearlyMetData->avg_humedad_interna,
            $yearlyMetData->min_temperatura_externa,
            $yearlyMetData->avg_temperatura_externa,
            $yearlyMetData->max_humedad_externa,
            $yearlyMetData->min_humedad_externa,
            $yearlyMetData->avg_humedad_externa,
            $yearlyMetData->max_presion_relativa,
            $yearlyMetData->min_presion_relativa,
            $yearlyMetData->avg_presion_relativa,
            $yearlyMetData->max_presion_absoluta,
            $yearlyMetData->min_presion_absoluta,
            $yearlyMetData->avg_presion_absoluta,
            $yearlyMetData->max_velocidad_viento,
            $yearlyMetData->min_velocidad_viento,
            $yearlyMetData->avg_velocidad_viento,
            $yearlyMetData->max_sensacion_termica,
            $yearlyMetData->min_sensacion_termica,
            $yearlyMetData->avg_sensacion_termica,
            $yearlyMetData->lluvia_24_horas_total,
            $yearlyMetData->actual_no_of_records,
            $yearlyMetData->expected_no_of_records,
            $yearlyMetData->created_at,
            $yearlyMetData->updated_at,

            // add additional field here
            $yearlyMetData->station->label,
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
        $fromYear = $this->request->query('fromYear');
        $toYear = $this->request->query('toYear');

        // convert station Ids from CSV format to an array
        $stationArray = str_getcsv($stationIds);

        // whereIn for station Ids
        // whereBetween used for From Year and To Year
        // records are order by station Id and Year and Month
        $query = YearlyMetData::select($this->fields)
                 ->whereIn('station_id', $stationArray)
                 ->whereBetween('year', [$fromYear, $toYear])
                 ->orderBy('station_id')
                 ->orderBy('year');

        return $query;
    }

    public function title(): string
    {
        return 'Yearly Met Data';
    }

    public function headings(): array
    {
        // add extra column urban
        $headers = $this->fields;
        
        // add additional header here
        array_push($headers, 'met_station');

        return $headers;
    }

}