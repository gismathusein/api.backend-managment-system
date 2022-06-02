<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'company_id',
        'department_id',
        'position_id',
        'fin_code',
        'serial_code',
        'serial_number',
        'name',
        'surname',
        'phone',
        'email',
        'address',
        'password',
        'status',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'pivot'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function photo(): MorphOne
    {
        return $this->morphOne(Fileable::class, 'fileable');
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'user_group')->withPivot('role')->distinct();
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'user_tasks')->withPivot('is_rejected', 'is_forwarded');
    }
    public function forwardedTasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'user_tasks')->withPivot('is_rejected' , 'is_forwarded');
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class , 'created_by' , 'id' );
    }
    public function createdTasksForGroups(){
        return $this->createdTasks();
    }
    public function position()
    {
        return $this->belongsTo(Position::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

}
