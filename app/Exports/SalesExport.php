<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SalesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $filter;

    public function __construct($filter = 'all')
    {
        $this->filter = $filter;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Sale::with('production')->orderBy('sale_date', 'desc');

        switch ($this->filter) {
            case 'taxable':
                $query->where('is_taxable', true);
                break;
            case 'non_taxable':
                $query->where('is_taxable', false);
                break;
            case 'all':
            default:
                // No filtering
                break;
        }

        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'SP Number',
            'Sale Date',
            'TBS Quantity',
            'KG Quantity',
            'Price per KG',
            'Total Amount',
            'Is Taxable',
            'Tax Percentage (%)',
            'Tax Amount',
            'Total with Tax',
            'Customer Name',
            'Customer Address',
            'Created At',
        ];
    }

    /**
     * @param mixed $sale
     * @return array
     */
    public function map($sale): array
    {
        return [
            $sale->sp_number,
            $sale->sale_date->format('Y-m-d'),
            $sale->tbs_quantity,
            $sale->kg_quantity,
            $sale->price_per_kg,
            $sale->total_amount,
            $sale->is_taxable ? 'Yes' : 'No',
            $sale->tax_percentage,
            $sale->tax_amount,
            $sale->total_amount + ($sale->tax_amount ?? 0),
            $sale->customer_name,
            $sale->customer_address,
            $sale->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
