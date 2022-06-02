<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubTask extends Model
{
    use HasFactory;

    protected $table = 'sub_tasks';

    protected $fillable = [
        'checklist_id',
        'task_id',
        'title'
    ];

    protected $visible = ['id','title','checklist_id'];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
}
