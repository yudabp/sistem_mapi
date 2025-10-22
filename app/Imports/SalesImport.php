<?php

namespace App\Imports;

use App\Models\Sale as SaleModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SalesImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new SaleModel([
            'sp_number' => $row['sp_number'] ?? null,
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
            'sp_number' => 'required',
            'kg_quantity' => 'required|numeric',
            'price_per_kg' => 'required|numeric',
            'sale_date' => 'required|date',
            'customer_name' => 'required',
            'customer_address' => 'required',
        ];
    }
}