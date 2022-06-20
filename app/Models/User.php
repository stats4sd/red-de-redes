<?php

namespace App\Models;

use App\Models\Met\File;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use CrudTrait;
    const ADMIN_TYPE = 'admin';
    const DEFAULT_TYPE = 'default';
    use Notifiable;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'users';

    protected $guarded = ['id'];
    protected $hidden = [
        'password', 'remember_token',
    ];


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function isAdmin() {
        return $this->type === self::ADMIN_TYPE;
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function files ()
    {
        return $this->hasMany(File::class, 'uploader_id');
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

}
