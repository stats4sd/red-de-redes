<?php

namespace App\Exports\Download\Met;

use Illuminate\Http\Request;
use App\Models\Met\DailyMetData;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class DailyMetDataExport implements FromQuery, WithTitle, WithHeadings, WithStrictNullComparison, WithMapping
{

    // HTTP request
    protected $request;

    // fields to be extracted
    protected $fields = [];

    // constructor to set HTTP request object to private variable
    public function __construct(Request $request = null)
    {
        logger("DailyMetDataExport.construct() starts...");

        $this->request = $request;

        $this->fields = [
            'station_id',
            'fecha',
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
    public function map($dailyMetData): array
    {
        return [
            $dailyMetData->station_id,
            $dailyMetData->fecha,
            //$dailyMetData->id,
            $dailyMetData->max_temperatura_interna,
            $dailyMetData->min_temperatura_interna,
            $dailyMetData->avg_temperatura_interna,
            $dailyMetData->max_humedad_interna,
            $dailyMetData->min_humedad_interna,
            $dailyMetData->avg_humedad_interna,
            $dailyMetData->max_temperatura_externa,
            $dailyMetData->min_temperatura_externa,
            $dailyMetData->avg_temperatura_externa,
            $dailyMetData->max_humedad_externa,
            $dailyMetData->min_humedad_externa,
            $dailyMetData->avg_humedad_externa,
            $dailyMetData->max_presion_relativa,
            $dailyMetData->min_presion_relativa,
            $dailyMetData->avg_presion_relativa,
            $dailyMetData->max_presion_absoluta,
            $dailyMetData->min_presion_absoluta,
            $dailyMetData->avg_presion_absoluta,
            $dailyMetData->max_velocidad_viento,
            $dailyMetData->min_velocidad_viento,
            $dailyMetData->avg_velocidad_viento,
            $dailyMetData->max_sensacion_termica,
            $dailyMetData->min_sensacion_termica,
            $dailyMetData->avg_sensacion_termica,
            $dailyMetData->lluvia_24_horas_total,
            $dailyMetData->actual_no_of_records,
            $dailyMetData->expected_no_of_records,
            $dailyMetData->created_at,
            $dailyMetData->updated_at,

            // add additional field here
            //$dailyMetData->survey->urban,
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
        $query = DailyMetData::select($this->fields)
                 ->whereIn('station_id', $stationArray)
                 ->whereBetween('fecha', [$strFromDate, $strToDate])
                 ->orderBy('station_id')
                 ->orderBy('fecha');

        return $query;
    }

    public function title(): string
    {
        return 'Daily Met Data';
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