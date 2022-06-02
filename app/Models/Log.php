<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = 'logs';

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'from_status',
        'to_status',
        'task_id',
        'change_at'
    ];


}
