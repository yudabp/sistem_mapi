<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the productions for the division.
     */
    public function productions(): HasMany
    {
        return $this->hasMany(Production::class, 'division_id');
    }

    /**
     * Get the employees for the division.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'department_id');
    }
}