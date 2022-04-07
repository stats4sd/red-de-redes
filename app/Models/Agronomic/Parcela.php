<?php

namespace App\Models\Agronomic;

use App\Models\Agronomic\Cultivo;
use App\Models\Agronomic\MuestraSuelo;
use App\Models\Submission;
use App\Models\Agronomic\Suelo;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Parcela extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'parcela';
    public $incrementing = false;
    protected $guarded = [];

    protected $casts = ['poligono_gps' => 'array'];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function submissions()
    {
        return $this->belongsTo(Submission::class, 'submission_id');
    }

    public function cultivos()
    {
        return $this->hasMany(Cultivo::class);
    }

    public function muestra_suelos()
    {
        return $this->hasMany(MuestraSuelo::class);
    }

    public function suelos()
    {
        return $this->hasMany(Suelo::class);
    }
}
