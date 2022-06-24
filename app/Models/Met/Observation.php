<?php

namespace App\Models\Met;

use App\Models\Met\MetData;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'observations';
    protected $guarded = ['id'];


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function data()
    {
        return $this->hasMany(MetData::class);
    }

    public function observation()
    {
        return $this->hasOne(File::class);
    }

}
