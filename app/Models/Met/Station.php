<?php

namespace App\Models\Met;

use App\Models\Met\MetData;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Station extends Model
{
    use CrudTrait, HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'stations';
    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function data()
    {
        return $this->hasMany(MetData::class, 'station_id');
    }

    public function file()
    {
        return $this->hasMany(File::class);
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

}
