<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    protected $fillable = [
        'sp_number', // Keep for backward compatibility
        'production_id',
        'tbs_quantity',
        'kg_quantity',
        'price_per_kg',
        'total_amount',
        'sales_proof_path',
        'sale_date',
        'customer_name',
        'customer_address',
    ];

    protected $casts = [
        'tbs_quantity' => 'decimal:2',
        'kg_quantity' => 'decimal:2',
        'price_per_kg' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'sale_date' => 'date',
    ];

    /**
     * Get the production that owns the sale.
     */
    public function production(): BelongsTo
    {
        return $this->belongsTo(Production::class, 'production_id');
    }

    /**
     * Get the SP number attribute (fallback to string field or production SP number).
     */
    public function getSpNumberAttribute(): string
    {
        if (isset($this->attributes['sp_number']) && !empty($this->attributes['sp_number'])) {
            return $this->attributes['sp_number'];
        }
        
        return $this->production?->sp_number ?? '';
    }

    /**
     * Get the TBS quantity attribute (fallback to production TBS quantity).
     */
    public function getTbsQuantityAttribute(): float
    {
        if (isset($this->attributes['tbs_quantity']) && !is_null($this->attributes['tbs_quantity'])) {
            return (float) $this->attributes['tbs_quantity'];
        }
        
        return $this->production?->tbs_quantity ?? 0;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-calculate total amount when price per kg or kg quantity changes
        static::saving(function ($sale) {
            if ($sale->kg_quantity && $sale->price_per_kg) {
                $sale->total_amount = $sale->kg_quantity * $sale->price_per_kg;
            }
        });
    }
}
