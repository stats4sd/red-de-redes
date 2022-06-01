<?php

namespace App\Models\Met;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{

    protected $guarded = [];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function observation()
    {
        return $this->belongsTo(Observation::class, 'observation_id');
    }

}
