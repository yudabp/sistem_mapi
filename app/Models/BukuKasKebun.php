<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuKasKebun extends Model
{
    protected $table = 'buku_kas_kebun';
    
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
        'kp_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    /**
     * Get the KP transaction that this BKK transaction is related to
     */
    public function keuanganPerusahaan()
    {
        return $this->belongsTo(KeuanganPerusahaan::class, 'kp_id');
    }
}