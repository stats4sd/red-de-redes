<?php

namespace App\Services;

class UnitConversions
{

    public static function getTempColumns()
    {
        return collect([
            'temperatura_externa',
            'hi_temp',
            'low_temp',
            'temperatura_interna'
        ]);
    }

    public static function getPressureColumns()
    {
        return collect([
            'presion_relativa'
        ]);
    }

    public static function getWindSpeedColumns()
    {
        return collect([
            'velocidad_viento',
            'hi_speed',
        ]);
    }

    public static function getRainfallColumns()
    {
        return collect([
            'rain',
            'lluvia_hora'
        ]);
    }

    /***** TEMPERATURE *****/
    public static function farenheitToCelcius(int|float $input): int|float
    {
        return ($input - 32) * 5 / 9;
    }

    /***** PRESSURE *****/

    public static function inhgToHpa(int|float $input): int|float
    {
        return $input * 33.86389;
    }

    public static function mmhgToHpa(int|float $input): int|float
    {
        return $input * 1.33322;
    }

    /***** WIND SPEED *****/

    public static function kmhToMs(int|float $input): int|float
    {
        return $input * 0.277778;
    }

    public static function mphToMs(int|float $input): int|float
    {
        return $input * 0.44704;
    }


    /***** RAINFALL *****/

    public static function inchToMm(int|float $input): int|float
    {
        return $input * 25.4;
    }


}
