<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyGroup extends Model
{
    use HasFactory;

    protected $table = 'company_groups';

    protected $fillable = [
        'group_id',
        'company_id'
    ];
}
