<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use App\Models\Submission;

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
}
