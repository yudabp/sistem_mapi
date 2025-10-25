<?php

namespace App\Imports;

use App\Models\Production as ProductionModel;
use App\Models\VehicleNumber;
use App\Models\Division;
use App\Models\Pks;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductionImport implements ToModel, WithHeadingRow, WithValidation
{
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
            'date' => $row['date'] ?? null,
            'sp_number' => $row['sp_number'] ?? null,
            'vehicle_id' => $vehicle ? $vehicle->id : null,
            'tbs_quantity' => $row['tbs_quantity'] ?? null,
            'kg_quantity' => $row['kg_quantity'] ?? null,
            'division_id' => $division ? $division->id : null,
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
