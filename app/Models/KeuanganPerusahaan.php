<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeuanganPerusahaan extends Model
{
    protected $table = 'keuangan_perusahaan';
    
    protected $fillable = [
        'transaction_date',
        'transaction_number',
        'transaction_type',
        'amount',
        'source_destination',
        'received_by',
        'proof_document_path',
        'notes',
        'category',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    /**
     * Get the BKK transactions related to this KP transaction
     */
    public function bukuKasKebun()
    {
        return $this->hasMany(BukuKasKebun::class, 'kp_id');
    }
}