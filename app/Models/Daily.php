<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Daily extends Model
{
    use CrudTrait;
    // use HasCompositePrimaryKey;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'daily_data';

    protected $guarded = ['id'];
    protected $casts = [
        'id_station',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function station()
    {
        return $this->belongsTo(Station::class, 'id_station');
    }

    // public function getIdStationAttribute($value)
    // {
    //     $label = Station::find($value)->first()->label;
    //     return $label;
    // }
}
