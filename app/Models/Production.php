<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Production extends Model
{
    protected $table = 'production'; // Specify the table name to match the migration
    
    protected $fillable = [
        'transaction_number',
        'date',
        'sp_number',
        'vehicle_number', // Keep for backward compatibility
        'vehicle_id',
        'tbs_quantity',
        'kg_quantity',
        'division', // Keep for backward compatibility
        'division_id',
        'pks', // Keep for backward compatibility
        'pks_id',
        'sp_photo_path',
    ];

    protected $casts = [
        'tbs_quantity' => 'decimal:2',
        'kg_quantity' => 'decimal:2',
        'date' => 'date',
    ];

    /**
     * Get the vehicle that owns the production.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(VehicleNumber::class, 'vehicle_id');
    }

    /**
     * Get the division that owns the production.
     */
    public function divisionRel(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    /**
     * Get the PKS that owns the production.
     */
    public function pksRel(): BelongsTo
    {
        return $this->belongsTo(Pks::class, 'pks_id');
    }

    /**
     * Get the sales for the production.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'production_id');
    }

    /**
     * Get the vehicle number attribute (fallback to string field).
     */
    public function getVehicleNumberAttribute(): string
    {
        return $this->vehicle?->number ?? $this->attributes['vehicle_number'] ?? '';
    }

    /**
     * Get the division name attribute (fallback to string field).
     */
    public function getDivisionNameAttribute(): string
    {
        return $this->divisionRel?->name ?? $this->attributes['division'] ?? '';
    }

    /**
     * Get the PKS name attribute (fallback to string field).
     */
    public function getPksNameAttribute(): string
    {
        return $this->pksRel?->name ?? $this->attributes['pks'] ?? '';
    }
}
