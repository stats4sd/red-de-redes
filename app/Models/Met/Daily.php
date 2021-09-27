<?php

namespace App\Models\Met;

use App\Models\Met\Station;
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

    protected $table = 'met_summary_daily';

    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function station()
    {
        return $this->belongsTo(Station::class, 'station_id');
    }

    // public function getIdStationAttribute($value)
    // {
    //     $label = Station::find($value)->first()->label;
    //     return $label;
    // }
}
