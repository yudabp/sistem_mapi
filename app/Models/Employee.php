<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $fillable = [
        'ndp',
        'name',
        'department', // Keep for backward compatibility
        'department_id',
        'position', // Keep for backward compatibility
        'position_id',
        'grade',
        'family_composition', // Keep for backward compatibility
        'family_composition_id',
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
        'family_composition_id' => 'integer',
        'hire_date' => 'date',
    ];

    /**
     * Get the department that owns the employee.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Get the position that owns the employee.
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    /**
     * Get the family composition that owns the employee.
     */
    public function familyComposition(): BelongsTo
    {
        return $this->belongsTo(FamilyComposition::class, 'family_composition_id');
    }

    /**
     * Get the department name attribute (fallback to string field).
     */
    public function getDepartmentNameAttribute(): string
    {
        return $this->department?->name ?? $this->attributes['department'] ?? '';
    }

    /**
     * Get the position name attribute (fallback to string field).
     */
    public function getPositionNameAttribute(): string
    {
        return $this->position?->name ?? $this->attributes['position'] ?? '';
    }

    /**
     * Get the family composition number attribute (fallback to string field).
     */
    public function getFamilyCompositionNumberAttribute(): int
    {
        if (isset($this->attributes['family_composition']) && !is_null($this->attributes['family_composition'])) {
            return (int) $this->attributes['family_composition'];
        }
        
        return $this->familyComposition?->number ?? 0;
    }
}
