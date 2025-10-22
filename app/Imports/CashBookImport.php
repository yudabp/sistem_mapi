<?php

namespace App\Imports;

use App\Models\FinancialTransaction as FinancialTransactionModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CashBookImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new FinancialTransactionModel([
            'transaction_date' => $row['transaction_date'] ?? null,
            'transaction_type' => $row['transaction_type'] ?? null,
            'amount' => $row['amount'] ?? null,
            'source_destination' => $row['purpose'] ?? null,
            'notes' => $row['notes'] ?? null,
            'category' => 'Cash Book',
            'transaction_number' => 'CB' . date('Ymd') . rand(1000, 9999),
        ]);
    }

    public function rules(): array
    {
        return [
            'transaction_date' => 'required|date',
            'transaction_type' => 'required|in:income,expense',
            'amount' => 'required|numeric',
            'purpose' => 'required',
        ];
    }
}