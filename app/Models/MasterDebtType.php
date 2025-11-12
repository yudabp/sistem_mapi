<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterDebtType extends Model
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
     * Get the debts for the debt type.
     */
    public function debts(): HasMany
    {
        return $this->hasMany(Debt::class);
    }

    /**
     * Scope a query to only include active debt types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
