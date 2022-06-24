<?php

namespace App\Models\Met;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class File extends Model
{

    use CrudTrait;

    protected $guarded = [];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function observation()
    {
        return $this->belongsTo(Observation::class, 'observation_id');
    }

    public function station()
    {
        return $this->belongsTo(Station::class, 'station_id');
    }

}
