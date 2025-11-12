<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Debt extends Model
{
    protected $fillable = [
        'amount',
        'sisa_hutang',
        'cicilan_per_bulan',
        'creditor',
        'due_date',
        'description',
        'debt_type_id',
        'employee_id',
        'proof_document_path',
        'status',
        'paid_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'sisa_hutang' => 'decimal:2',
        'cicilan_per_bulan' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    /**
     * Get the debt type that owns the debt.
     */
    public function debtType(): BelongsTo
    {
        return $this->belongsTo(MasterDebtType::class, 'debt_type_id');
    }

    /**
     * Get the employee that owns the debt.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the payments for the debt.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(HutangPembayaran::class);
    }

    /**
     * Get the BKK transactions for the debt.
     */
    public function bkkTransactions(): HasMany
    {
        return $this->hasMany(BukuKasKebun::class, 'debt_id');
    }

    /**
     * Get the total paid amount.
     */
    public function getTotalPaidAttribute(): float
    {
        return \DB::table('hutang_pembayaran')->where('debt_id', $this->id)->sum('payment_amount');
    }

    /**
     * Get the remaining debt amount.
     */
    public function getRemainingDebtAttribute(): float
    {
        return max(0, $this->amount - $this->total_paid);
    }

    /**
     * Check if the debt is fully paid.
     */
    public function getIsFullyPaidAttribute(): bool
    {
        return $this->remaining_debt <= 0;
    }

    /**
     * Scope a query to only include unpaid debts.
     */
    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    /**
     * Scope a query to only include paid debts.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope a query to only include debts with remaining balance.
     */
    public function scopeWithRemainingBalance($query)
    {
        return $query->where('sisa_hutang', '>', 0);
    }
}
