<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'status'
    ];

    protected $hidden = ['pivot'];

    public function logo(): MorphOne
    {
        return $this->morphOne(Fileable::class, 'fileable');
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class,'company_id','id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class,'company_id','id');
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'company_group');
    }



}
