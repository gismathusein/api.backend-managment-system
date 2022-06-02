<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Fileable extends Model
{
    use HasFactory;
    protected $table = 'fileables';

    protected $fillable = [
        'file_id',
        'fileable_id',
        'fileable_type',
        'path',
        'original_name',
        'mime_type',
        'user_id'
    ];

    protected $visible = [
        'file_id',
        'path',
        'original_name',
        'user_id'
    ];

    public  function fileable(): MorphTo
    {
        return $this->morphTo();
    }
}
