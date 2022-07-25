<?php

namespace App\Models\Met;

use App\Models\Met\MetData;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Station extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'stations';
    protected $guarded = ['id'];

    protected $fakeColumns = ['constants'];

    protected $casts = [
        'constants' => 'array'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function data()
    {
        return $this->hasMany(MetData::class, 'station_id');
    }

}
