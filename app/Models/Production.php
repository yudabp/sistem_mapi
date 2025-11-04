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

    /**
     * Generate automatic transaction number
     * Format: TN + MM + YY + 4 digit urut
     * Example: TN10250001 (for October 2025, sequence 0001)
     */
    public static function generateTransactionNumber(): string
    {
        $now = now();
        $monthYear = $now->format('my'); // mY format (1025 for October 2025)

        // Get the last transaction number for this month
        $lastTransaction = self::where('transaction_number', 'like', 'TN' . $monthYear . '%')
            ->orderBy('transaction_number', 'desc')
            ->first();

        if ($lastTransaction) {
            // Extract the last 4 digits and increment
            $lastSequence = (int)substr($lastTransaction->transaction_number, -4);
            $newSequence = $lastSequence + 1;
        } else {
            // Start with 0001 for new month or if no transactions exist
            $newSequence = 1;
        }

        // Format: TN + MM + YY + 4 digit sequence (padded with zeros)
        return 'TN' . $monthYear . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate transaction number when creating new record
        static::creating(function ($production) {
            if (empty($production->transaction_number)) {
                $production->transaction_number = self::generateTransactionNumber();
            }
        });
    }
}
