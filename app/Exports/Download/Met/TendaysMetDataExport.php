<?php

namespace App\Exports\Download\Met;

use Illuminate\Http\Request;
use App\Models\Met\TendaysMetData;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class TendaysMetDataExport implements FromQuery, WithTitle, WithHeadings, WithStrictNullComparison, WithMapping
{
    use Exportable;

    protected array $query;

    // fields to be extracted
    protected $fields = [];

    // constructor to set HTTP request object to private variable
    public function __construct(array $query = null)
    {
        logger("TendaysMetDataExport.construct() starts...");

        $this->query = $query;

        $this->fields = [
            'station_id',
            'year_and_month',
            'part',
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
    public function map($tendaysMetData): array
    {
        return [
            $tendaysMetData->station_id,
            $tendaysMetData->year_and_month,
            $tendaysMetData->part,
            //$tendaysMetData->id,
            $tendaysMetData->max_temperatura_interna,
            $tendaysMetData->min_temperatura_interna,
            $tendaysMetData->avg_temperatura_interna,
            $tendaysMetData->max_humedad_interna,
            $tendaysMetData->min_humedad_interna,
            $tendaysMetData->avg_humedad_interna,
            $tendaysMetData->min_temperatura_externa,
            $tendaysMetData->avg_temperatura_externa,
            $tendaysMetData->max_humedad_externa,
            $tendaysMetData->min_humedad_externa,
            $tendaysMetData->avg_humedad_externa,
            $tendaysMetData->max_presion_relativa,
            $tendaysMetData->min_presion_relativa,
            $tendaysMetData->avg_presion_relativa,
            $tendaysMetData->max_presion_absoluta,
            $tendaysMetData->min_presion_absoluta,
            $tendaysMetData->avg_presion_absoluta,
            $tendaysMetData->max_velocidad_viento,
            $tendaysMetData->min_velocidad_viento,
            $tendaysMetData->avg_velocidad_viento,
            $tendaysMetData->max_sensacion_termica,
            $tendaysMetData->min_sensacion_termica,
            $tendaysMetData->avg_sensacion_termica,
            $tendaysMetData->lluvia_24_horas_total,
            $tendaysMetData->actual_no_of_records,
            $tendaysMetData->expected_no_of_records,
            $tendaysMetData->created_at,
            $tendaysMetData->updated_at,

            // add additional field here
            $tendaysMetData->station->label,
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
        $query = TendaysMetData::select($this->fields)
                 ->whereIn('station_id', $stationIds)
                 ->whereBetween('year_and_month', [$strFromYearAndMonth, $strToYearAndMonth])
                 ->orderBy('station_id')
                 ->orderBy('year_and_month')
                 ->orderBy('part');

        return $query;
    }

    public function title(): string
    {
        return 'Tendays Met Data';
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
