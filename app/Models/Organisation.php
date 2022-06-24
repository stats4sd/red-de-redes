<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Met\Station;

class Organisation extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $guarded = [];


    public function stations()
    {
        return $this->hasMany(Station::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
