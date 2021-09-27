<?php

namespace App\Exports;

use App\Daily;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DailyExport implements WithHeadings, FromQuery
{
    use Exportable;

    public function __construct(Array $station_ids)
    {
    	$this->station_ids = $station_ids;
    }

    public function headings() : array{
    	return [

    		'Fecha',
    		'Id Station',
    		'Max temperature interna',
            'Min temperature interna',
            'Max humedad interna',
            'Min humidad interna',
            'Max temperatura externa',
            'Min temperatura externa',
            'Max humedad externa',
            'Min humedad externa',
            'Max presion relativa',
            'Min presion relativa',
            'Max presion absoluta',
            'Min presion absoluta',
            'Max velocidad viento',
            'Min velocidad viento',
            'Max sensacion termica',
            'Min sensacion termica',
            'LLuvia 24 horas Total',
            'id.station from stations',
            'Type of Station'


    	];
    }

    public function query()

    {
    	return Daily::whereIn('station_id',$this->station_ids)->orderBy('fecha')->join('stations','station_id','=','stations.id');
    }




}
