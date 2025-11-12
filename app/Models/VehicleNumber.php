<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleNumber extends Model
{
    protected $fillable = [
        'number',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the productions for the vehicle.
     */
    public function productions(): HasMany
    {
        return $this->hasMany(Production::class, 'vehicle_id');
    }
}