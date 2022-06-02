<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';

    protected $fillable = [
        'name',
        'group_type',
        'status'
    ];

    protected $hidden = ['pivot'];

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_group')->select('companies.id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_group')->withPivot('role')->select('users.id', 'user_group.role');
    }

    public function groupUsers()
    {
        return $this->belongsToMany(User::class, 'user_group')->withPivot('role')->select(['user_group.role as group_role', 'users.*']);
    }

    public function groupTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'group_id', 'id')
            ->where('status', \TaskStatus::UNKNOWN)
            ->where('tag', \TaskTag::UNKNOWN)
            ->with('attachments');

    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'group_id', 'id');
    }


}
