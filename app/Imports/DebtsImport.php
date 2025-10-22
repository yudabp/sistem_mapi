<?php

namespace App\Imports;

use App\Models\Debt as DebtModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DebtsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new DebtModel([
            'amount' => $row['amount'] ?? null,
            'creditor' => $row['creditor'] ?? null,
            'due_date' => $row['due_date'] ?? null,
            'description' => $row['description'] ?? null,
            'status' => $row['status'] ?? 'unpaid', // Default to unpaid
            'paid_date' => $row['paid_date'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric',
            'creditor' => 'required',
            'due_date' => 'required|date',
            'description' => 'required',
        ];
    }
}