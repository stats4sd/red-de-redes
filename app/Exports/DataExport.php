<?php

namespace App\Exports;

use App\Models\Met\MetData;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataExport implements WithHeadings, FromQuery
{

    use Exportable;

    public function __construct(Array $id_stations)
    {
    	$this->id_stations = $id_stations;
    }

    public function headings() : array{
    	return [

    		'Fecha/Hora',
    		'Intervalo',
    		'Temperatura Interna',
            'Humedad Interna(%)',
            'Temperatura Externa(°C)',
            'Humedad Externa(%)',
            'Presion Relativa(hpa)',
            'Presion Absoluta(hpa)',
            'Velocidad del viento(m/s)',
            'Sensacion Termica(°C)',
            'Rafaga(m/s)',
            'Direccion del viento',
            'Punto de Rocio(°C)',
            'Lluvia hora(mm)',
            'Lluvia 24 horas(mm)',
            'Lluvia semana(mm)',
            'Lluvia mes(mm)',
            'Lluvia Total(mm)',

            'Hi_Temp',
            'Low_Temp',
            'Wind_Cod',
            'Wind_Run',
            'Hi_Speed',
            'Hi_Dir',
            'Wind_Cod_Dom',
            'Wind_Chill',
            'Heat_Index',
            'THW_Index',
            'THSW_Index',
            'Rain',
            'Solar_Rad.',
            'Solar_Energy',
            'Hi_Solar_Rad.',
            'UV_Index',
            'UV_Dose',
            'Hi_UV',
            'Heat_D-D',
            'Cool_D-D',
            'In_Dew',
            'In_Heat',
            'In_EMC',
            'In_Air_Density',
            'ET',
            'Soil_1_Moist.',
            'Soil_2_Moist.',
            'Leaf_Wet_1',
            'Wind_Samp',
            'Wind_Tx',
            'ISS_Recept',
            'id_station',
            'Id Type Station',
            'Type Station'



    	];
    }

    public function query()

    {
    	return MetData::whereIn('id_station',$this->id_stations)->orderBy('fecha_hora')->join('stations','id_station','=','stations.id');
    }


}
