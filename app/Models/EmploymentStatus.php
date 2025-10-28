<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmploymentStatus extends Model
{
    protected $fillable = [
        'name',
        'value',
        'description',
        'is_active',
    ];

    /**
     * Get the employees for the employment status.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
