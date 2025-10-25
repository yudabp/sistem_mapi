<?php

namespace App\Imports;

use App\Models\Sale as SaleModel;
use App\Models\Production;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class SalesImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Find production_id by SP number
        $production = null;
        if (!empty($row['sp_number'])) {
            $production = Production::where('sp_number', $row['sp_number'])->first();
        }

        return new SaleModel([
            'production_id' => $production ? $production->id : null,
            'tbs_quantity' => $row['tbs_quantity'] ?? null,
            'kg_quantity' => $row['kg_quantity'] ?? null,
            'price_per_kg' => $row['price_per_kg'] ?? null,
            'total_amount' => $row['total_amount'] ?? null,
            'sale_date' => $row['sale_date'] ?? null,
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