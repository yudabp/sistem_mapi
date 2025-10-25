<?php

namespace App\Imports;

use App\Models\Debt as DebtModel;
use App\Models\MasterDebtType;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DebtsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Find or create debt_type
        $debtType = null;
        if (!empty($row['debt_type'])) {
            $debtType = MasterDebtType::firstOrCreate(
                ['name' => $row['debt_type']],
                [
                    'name' => $row['debt_type'],
                    'description' => 'Auto-created from import',
                    'is_active' => true,
                ]
            );
        }

        return new DebtModel([
            'amount' => $row['amount'] ?? null,
            'sisa_hutang' => $row['sisa_hutang'] ?? $row['amount'] ?? null, // Default remaining debt to full amount if not specified
            'cicilan_per_bulan' => $row['cicilan_per_bulan'] ?? null,
            'debt_type_id' => $debtType ? $debtType->id : null,
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
