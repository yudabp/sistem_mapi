<?php

namespace App\Imports;

use App\Models\BukuKasKebun;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class CashBookImport implements ToModel, WithHeadingRow, WithValidation
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
        return new BukuKasKebun([
            'transaction_date' => $row['transaction_date'] ? $this->parseFlexibleDate($row['transaction_date']) : null,
            'transaction_type' => $row['transaction_type'] ?? null,
            'amount' => $row['amount'] ?? null,
            'source_destination' => $row['purpose'] ?? null,
            'notes' => $row['notes'] ?? null,
            'category' => $row['category'] ?? 'Cash Book', // Allow custom category or default to 'Cash Book'
            'transaction_number' => $row['transaction_number'] ?? 'BKK-CB' . date('Ymd') . rand(1000, 9999), // Use BKK prefix for cash book
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