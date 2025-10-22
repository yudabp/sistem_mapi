<?php

namespace App\Imports;

use App\Models\Production as ProductionModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductionImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new ProductionModel([
            'transaction_number' => $row['transaction_number'] ?? null,
            'date' => $row['date'] ?? null,
            'sp_number' => $row['sp_number'] ?? null,
            'vehicle_number' => $row['vehicle_number'] ?? null,
            'tbs_quantity' => $row['tbs_quantity'] ?? null,
            'kg_quantity' => $row['kg_quantity'] ?? null,
            'division' => $row['division'] ?? null,
            'pks' => $row['pks'] ?? null,
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