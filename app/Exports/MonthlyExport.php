<?php

namespace App\Exports;

use App\Monthly;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonthlyExport implements WithHeadings, FromQuery
{
    use Exportable;

    public function __construct(Array $id_stations)
    {
    	$this->id_stations = $id_stations;
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
    	return Monthly::whereIn('id_station',$this->id_stations)->orderBy('fecha')->join('stations','id_station','=','stations.id');
    }




}
