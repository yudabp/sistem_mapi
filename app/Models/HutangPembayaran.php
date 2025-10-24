<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HutangPembayaran extends Model
{
    protected $table = 'hutang_pembayaran';

    protected $fillable = [
        'debt_id',
        'payment_amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'notes',
        'proof_document_path',
        'bkk_id',
    ];

    protected $casts = [
        'payment_amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    /**
     * Get the debt that owns the payment.
     */
    public function debt(): BelongsTo
    {
        return $this->belongsTo(Debt::class);
    }

    /**
     * Get the BKK transaction that owns the payment.
     */
    public function bkkTransaction(): BelongsTo
    {
        return $this->belongsTo(BukuKasKebun::class, 'bkk_id');
    }
}
