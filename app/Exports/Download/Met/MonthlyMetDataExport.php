<?php

namespace App\Exports\Download\Met;

use Illuminate\Http\Request;
use App\Models\Met\MonthlyMetData;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class MonthlyMetDataExport implements FromQuery, WithTitle, WithHeadings, WithStrictNullComparison, WithMapping
{
    protected array $query;

    // fields to be extracted
    protected $fields = [];

    // constructor to set HTTP request object to private variable
    public function __construct(array $query = null)
    {
        $this->query = $query;

        $this->fields = [
            'station_id',
            'year_and_month',
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
    public function map($monthlyMetData): array
    {
        return [
            $monthlyMetData->station_id,
            $monthlyMetData->year_and_month,
            //$monthlyMetData->id,
            $monthlyMetData->max_temperatura_interna,
            $monthlyMetData->min_temperatura_interna,
            $monthlyMetData->avg_temperatura_interna,
            $monthlyMetData->max_humedad_interna,
            $monthlyMetData->min_humedad_interna,
            $monthlyMetData->avg_humedad_interna,
            $monthlyMetData->min_temperatura_externa,
            $monthlyMetData->avg_temperatura_externa,
            $monthlyMetData->max_humedad_externa,
            $monthlyMetData->min_humedad_externa,
            $monthlyMetData->avg_humedad_externa,
            $monthlyMetData->max_presion_relativa,
            $monthlyMetData->min_presion_relativa,
            $monthlyMetData->avg_presion_relativa,
            $monthlyMetData->max_presion_absoluta,
            $monthlyMetData->min_presion_absoluta,
            $monthlyMetData->avg_presion_absoluta,
            $monthlyMetData->max_velocidad_viento,
            $monthlyMetData->min_velocidad_viento,
            $monthlyMetData->avg_velocidad_viento,
            $monthlyMetData->max_sensacion_termica,
            $monthlyMetData->min_sensacion_termica,
            $monthlyMetData->avg_sensacion_termica,
            $monthlyMetData->lluvia_24_horas_total,
            $monthlyMetData->actual_no_of_records,
            $monthlyMetData->expected_no_of_records,
            $monthlyMetData->created_at,
            $monthlyMetData->updated_at,

            // add additional field here
            $monthlyMetData->station->label,
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        // get station Ids, from month, from year, to month, to year from request query
        // Vue component should have validated all of them, each of them should have value
        $stationIds = $this->query['stations'];
        $fromMonth = "01";
        $fromYear = $this->query['fromYear'];
        $toMonth = "12";
        $toYear = $this->query['toYear'];

        // prepare From Year and Month
        $strFromYearAndMonth = $fromYear . $fromMonth;

        // prepare To Year and Month
        $strToYearAndMonth = $toYear . $toMonth;

        // whereIn for station Ids
        // whereBetween used for From Year and Month and To Year and Month
        // records are order by station Id and Year and Month
        $query = MonthlyMetData::select($this->fields)
                 ->whereIn('station_id', $stationIds)
                 ->whereBetween('year_and_month', [$strFromYearAndMonth, $strToYearAndMonth])
                 ->orderBy('station_id')
                 ->orderBy('year_and_month');

        return $query;
    }

    public function title(): string
    {
        return 'Monthly Met Data';
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
