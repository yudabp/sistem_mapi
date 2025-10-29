<?php

namespace App\Imports;

use App\Models\Debt as DebtModel;
use App\Models\MasterDebtType;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class DebtsImport implements ToModel, WithHeadingRow, WithValidation
{
    private function parseFlexibleDate($dateValue)
    {
        if (empty($dateValue)) {
            return null;
        }
        
        try {
            // Try Carbon's default parsing first
            return Carbon::parse($dateValue)->format('Y-m-d');
        } catch (\Exception $e) {
            // If that fails, try common ambiguous formats
            // Handle MM/DD/YYYY or DD/MM/YYYY formats
            if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $dateValue, $matches)) {
                $part1 = (int)$matches[1];
                $part2 = (int)$matches[2];
                $year = (int)$matches[3];
                
                // Try assuming first part is month
                if ($part1 >= 1 && $part1 <= 12) {
                    try {
                        return Carbon::createFromDate($year, $part1, $part2)->format('Y-m-d');
                    } catch (\Exception $e) {
                        // Continue to next attempt
                    }
                }
                
                // Try assuming second part is month
                if ($part2 >= 1 && $part2 <= 12) {
                    try {
                        return Carbon::createFromDate($year, $part2, $part1)->format('Y-m-d');
                    } catch (\Exception $e) {
                        // Continue to next attempt
                    }
                }
            }
        }
        
        // If all parsing attempts fail, return null
        return null;
    }

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
            'due_date' => $row['due_date'] ? $this->parseFlexibleDate($row['due_date']) : null,
            'description' => $row['description'] ?? null,
            'status' => $row['status'] ?? 'unpaid', // Default to unpaid
            'paid_date' => $row['paid_date'] ? $this->parseFlexibleDate($row['paid_date']) : null,
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
