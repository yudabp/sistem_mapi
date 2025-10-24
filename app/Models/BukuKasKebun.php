<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'expense_category_id',
        'debt_id',
        'kp_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    /**
     * Get the KP transaction that this BKK transaction is related to
     */
    public function keuanganPerusahaan(): BelongsTo
    {
        return $this->belongsTo(KeuanganPerusahaan::class, 'kp_id');
    }

    /**
     * Get the expense category that owns the BKK transaction.
     */
    public function expenseCategory(): BelongsTo
    {
        return $this->belongsTo(MasterBkkExpenseCategory::class, 'expense_category_id');
    }

    /**
     * Get the debt that owns the BKK transaction.
     */
    public function debt(): BelongsTo
    {
        return $this->belongsTo(Debt::class);
    }

    /**
     * Get the debt payments for the BKK transaction.
     */
    public function debtPayments(): HasMany
    {
        return $this->hasMany(HutangPembayaran::class, 'bkk_id');
    }

    /**
     * Scope a query to only include expense transactions.
     */
    public function scopeExpense($query)
    {
        return $query->where('transaction_type', 'expense');
    }

    /**
     * Scope a query to only include income transactions.
     */
    public function scopeIncome($query)
    {
        return $query->where('transaction_type', 'income');
    }

    /**
     * Scope a query to only include debt payment transactions.
     */
    public function scopeDebtPayment($query)
    {
        return $query->whereHas('expenseCategory', function ($query) {
            $query->where('is_debt_payment', true);
        })->orWhereNotNull('debt_id');
    }

    /**
     * Scope a query to only include regular expense transactions.
     */
    public function scopeRegularExpense($query)
    {
        return $query->whereHas('expenseCategory', function ($query) {
            $query->where('is_debt_payment', false);
        })->whereNull('debt_id');
    }
}