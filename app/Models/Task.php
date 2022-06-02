<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'description',
        'status',
        'group_id',
        'created_by',
        'checklists',
        'tag'
    ];


    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_tasks', 'task_id', 'user_id')->select('users.id');
    }

    public function taskUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_tasks')->withPivot('is_rejected', 'is_forwarded');
    }

    public function subTasks(): HasMany
    {
        return $this->hasMany(SubTask::class, 'task_id', 'id');
    }

    public function checkList()
    {
        return $this->hasMany(CheckList::class, 'task_id', 'id');
    }

    public function groups()
    {
        return $this->hasMany(Task::class, 'group_id', 'id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Fileable::class, 'fileable');
    }

    public function notes()
    {
        return $this->hasMany(Task::class, 'task_id', 'id');
    }
}
