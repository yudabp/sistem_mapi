<?php

namespace App\Imports;

use App\Models\Production as ProductionModel;
use App\Models\VehicleNumber;
use App\Models\Division;
use App\Models\Pks;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class ProductionImport implements ToModel, WithHeadingRow, WithValidation
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
        // Find or create vehicle_id
        $vehicle = null;
        if (!empty($row['vehicle_number'])) {
            $vehicle = VehicleNumber::firstOrCreate(
                ['number' => $row['vehicle_number']],
                [
                    'number' => $row['vehicle_number'],
                    'description' => 'Auto-created from import',
                    'is_active' => true,
                ]
            );
        }

        // Find or create division_id
        $division = null;
        if (!empty($row['division'])) {
            $division = Division::firstOrCreate(
                ['name' => $row['division']],
                [
                    'name' => $row['division'],
                    'description' => 'Auto-created from import',
                    'is_active' => true,
                ]
            );
        }

        // Find or create pks_id
        $pks = null;
        if (!empty($row['pks'])) {
            $pks = Pks::firstOrCreate(
                ['name' => $row['pks']],
                [
                    'name' => $row['pks'],
                    'description' => 'Auto-created from import',
                    'is_active' => true,
                ]
            );
        }

        return new ProductionModel([
            'transaction_number' => $row['transaction_number'] ?? null,
            'date' => $row['date'] ? $this->parseFlexibleDate($row['date']) : null,
            'sp_number' => $row['sp_number'] ?? null,
            'vehicle_number' => $row['vehicle_number'] ?? null, // Backward compatibility
            'vehicle_id' => $vehicle ? $vehicle->id : null,
            'tbs_quantity' => $row['tbs_quantity'] ?? null,
            'kg_quantity' => $row['kg_quantity'] ?? null,
            'division' => $row['division'] ?? null, // Backward compatibility
            'division_id' => $division ? $division->id : null,
            'pks' => $row['pks'] ?? null, // Backward compatibility
            'pks_id' => $pks ? $pks->id : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'transaction_number' => 'required|unique:production,transaction_number',
            'date' => 'required|date',
            'sp_number' => 'required',
            'vehicle_number' => 'required',
            'tbs_quantity' => 'required|numeric',
            'kg_quantity' => 'required|numeric',
            'division' => 'required',
            'pks' => 'required',
        ];
    }
}
