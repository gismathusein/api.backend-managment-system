<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CheckList extends Model
{
    use HasFactory;

    protected $table = 'check_lists';

    protected $fillable = [
        'task_id',
        'name'
    ];

    public function subTasks(): HasMany
    {
        return $this->hasMany(CheckList::class, 'id', 'checklist_id');
    }


}
