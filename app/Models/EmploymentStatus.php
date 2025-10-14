<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploymentStatus extends Model
{
    protected $fillable = [
        'name',
        'value',
        'description',
        'is_active',
    ];
}
