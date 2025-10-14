<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'ndp',
        'name',
        'department',
        'position',
        'grade',
        'family_composition',
        'monthly_salary',
        'status',
        'hire_date',
        'address',
        'phone',
        'email',
    ];

    protected $casts = [
        'monthly_salary' => 'decimal:2',
        'family_composition' => 'integer',
        'hire_date' => 'date',
    ];
}
