<?php

namespace App\Models\Met;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stats4sd\FileUtil\Models\Traits\HasUploadFields;

class File extends Model
{

    use CrudTrait, HasUploadFields;

    protected $guarded = [];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'station_id');
    }

    public function setDataFileAttribute($value): void
    {
        $this->uploadFileWithNames(
            $value,
            'data_file',
            'public',
            'rawfiles');
    }

}
