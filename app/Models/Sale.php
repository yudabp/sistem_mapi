<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'sp_number',
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
}
