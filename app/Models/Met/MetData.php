<?php

namespace App\Models\Met;

use App\Models\Met\Observation;
use App\Models\Met\Station;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class MetData extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------
    */

    # use HasCompositePrimaryKey;
    protected $primaryKey = 'id';
    protected $table = 'data';
    protected $guarded = ['id'];


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function station()
    {
        return $this->belongsTo(Station::class, 'id_station');
    }

    public function observation()
    {
        return $this->belongsTo(Observation::class, 'observation_id');
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
