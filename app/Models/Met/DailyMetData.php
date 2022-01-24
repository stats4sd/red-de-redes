<?php

namespace App\Models\Met;

use App\Models\Met\Observation;
use App\Models\Met\Station;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class DailyMetData extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------
    */

    # use HasCompositePrimaryKey;
    protected $primaryKey = 'id';
    protected $table = 'daily_met_data';
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


    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
