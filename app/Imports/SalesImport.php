<?php

namespace App\Imports;

use App\Models\Sale as SaleModel;
use App\Models\Production;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class SalesImport implements ToModel, WithHeadingRow, WithValidation
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
        // Find production_id by SP number
        $production = null;
        if (!empty($row['sp_number'])) {
            $production = Production::where('sp_number', $row['sp_number'])->first();
        }

        return new SaleModel([
            'sp_number' => $row['sp_number'] ?? null, // Backward compatibility
            'production_id' => $production ? $production->id : null,
            'tbs_quantity' => $row['tbs_quantity'] ?? null,
            'kg_quantity' => $row['kg_quantity'] ?? null,
            'price_per_kg' => $row['price_per_kg'] ?? null,
            'total_amount' => $row['total_amount'] ?? null,
            'sale_date' => $row['sale_date'] ? $this->parseFlexibleDate($row['sale_date']) : null,
            'customer_name' => $row['customer_name'] ?? null,
            'customer_address' => $row['customer_address'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'sp_number' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Production::where('sp_number', $value)->exists()) {
                        $fail("The SP number {$value} does not exist in production records.");
                    }
                },
            ],
            'kg_quantity' => 'required|numeric',
            'price_per_kg' => 'required|numeric',
            'sale_date' => 'required|date',
            'customer_name' => 'required',
            'customer_address' => 'required',
        ];
    }
}