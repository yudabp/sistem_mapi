<?php

namespace App\Imports;

use App\Models\KeuanganPerusahaan;
use App\Models\BukuKasKebun;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class FinancialImport implements ToModel, WithHeadingRow, WithValidation
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
        // Determine if transaction belongs to KP (Keuangan Perusahaan) or BKK (Buku Kas Kebun)
        // Based on the category, similar to the migration logic
        $isKP = $this->isKPTransaction((object)$row);
        
        if ($isKP) {
            return new KeuanganPerusahaan([
                'transaction_date' => $row['transaction_date'] ? $this->parseFlexibleDate($row['transaction_date']) : null,
                'transaction_type' => $row['transaction_type'] ?? null,
                'amount' => $row['amount'] ?? null,
                'source_destination' => $row['source_destination'] ?? null,
                'received_by' => $row['received_by'] ?? null,
                'notes' => $row['notes'] ?? null,
                'category' => $row['category'] ?? null,
                'transaction_number' => $row['transaction_number'] ?? 'KP-' . date('Ymd') . rand(1000, 9999), // Use provided transaction number or generate
            ]);
        } else {
            return new BukuKasKebun([
                'transaction_date' => $row['transaction_date'] ? $this->parseFlexibleDate($row['transaction_date']) : null,
                'transaction_type' => $row['transaction_type'] ?? null,
                'amount' => $row['amount'] ?? null,
                'source_destination' => $row['source_destination'] ?? null,
                'received_by' => $row['received_by'] ?? null,
                'notes' => $row['notes'] ?? null,
                'category' => $row['category'] ?? null,
                'transaction_number' => $row['transaction_number'] ?? 'BKK-' . date('Ymd') . rand(1000, 9999), // Use provided transaction number or generate
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'transaction_date' => 'required|date',
            'transaction_type' => 'required|in:income,expense',
            'amount' => 'required|numeric',
            'source_destination' => 'required',
            'category' => 'required',
        ];
    }
    
    /**
     * Determine if a transaction belongs to KP (Keuangan Perusahaan) or BKK (Buku Kas Kebun)
     */
    private function isKPTransaction($transaction)
    {
        // KP categories: Company-level financial transactions
        $kpCategories = [
            'Sales Revenue',
            'Personnel Cost',
            'Administrative Cost',
            'Financial Cost',
            'Tax',
            'Investment',
            'Loan',
            'Other Income',
            'Other Expense'
        ];
        
        // BKK categories: Garden/plantation-level operational transactions
        $bkkCategories = [
            'Operational Cost',
            'Maintenance Cost',
            'Logistics Cost',
            'Harvesting Cost',
            'Fertilizer Cost',
            'Pest Control Cost',
            'Transportation Cost',
            'Fuel Cost'
        ];
        
        // Check if category is in KP list
        if (in_array($transaction->category ?? '', $kpCategories)) {
            return true;
        }
        
        // Check if category is in BKK list
        if (in_array($transaction->category ?? '', $bkkCategories)) {
            return false;
        }
        
        // Default logic: Income transactions usually go to KP, operational expenses to BKK
        return $transaction->transaction_type === 'income';
    }
}