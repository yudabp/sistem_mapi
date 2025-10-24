<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterBkkExpenseCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_debt_payment',
        'is_active',
    ];

    protected $casts = [
        'is_debt_payment' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the BKK transactions for the category.
     */
    public function bkkTransactions(): HasMany
    {
        return $this->hasMany(BukuKasKebun::class, 'expense_category_id');
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include debt payment categories.
     */
    public function scopeDebtPayment($query)
    {
        return $query->where('is_debt_payment', true);
    }

    /**
     * Scope a query to only include regular expense categories.
     */
    public function scopeRegularExpense($query)
    {
        return $query->where('is_debt_payment', false);
    }
}
