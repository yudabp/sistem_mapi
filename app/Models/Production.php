<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $table = 'production'; // Specify the table name to match the migration

    protected $fillable = [
        'transaction_number',
        'date',
        'sp_number',
        'vehicle_number',
        'tbs_quantity',
        'kg_quantity',
        'division',
        'pks',
        'sp_photo_path',
    ];

    protected $casts = [
        'tbs_quantity' => 'decimal:2',
        'kg_quantity' => 'decimal:2',
        'date' => 'date',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class, 'sp_number', 'sp_number');
    }
}
