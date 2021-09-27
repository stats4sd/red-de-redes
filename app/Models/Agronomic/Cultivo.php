<?php

namespace App\Models\Agronomic;

use App\Models\Agronomic\Enfermedade;
use App\Models\Agronomic\Fenologia;
use App\Models\LkpCultivo;
use App\Models\LkpVariedad;
use App\Models\Agronomic\ManejoParcela;
use App\Models\Agronomic\Parcela;
use App\Models\Agronomic\Plaga;
use App\Models\Agronomic\Rendimento;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Cultivo extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'cultivos';
    protected $guarded = ['id'];


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function lkp_cultivos()
    {
        return $this->belongsTo(LkpCultivo::class);
    }

    public function lkp_variedad()
    {
        return $this->belongsTo(LkpVariedad::class);
    }

    public function parcela()
    {
        return $this->belongsTo(Parcela::class);
    }

    // Crop Modules
    public function fenologias()
    {
        return $this->hasMany(Fenologia::class);
    }

    public function manejo_parcelas()
    {
        return $this->hasMany(ManejoParcela::class);
    }

    public function plagas()
    {
        return $this->hasMany(Plaga::class);
    }

    public function enfermedades()
    {
        return $this->hasMany(Enfermedade::class);
    }

    public function rendimentos()
    {
        return $this->hasMany(Rendimento::class);
    }
}
