<?php

namespace App\Exports;

use App\Models\Sale as SaleModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\Auth;
use DateTime;

class SalesExportWithHeaders implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $user;

    public function __construct($startDate = null, $endDate = null)
    {
        // Convert dates from DD-MM-YYYY to Y-m-d format if needed
        if ($startDate) {
            $dateObj = DateTime::createFromFormat('d-m-Y', $startDate);
            if ($dateObj) {
                $this->startDate = $dateObj->format('Y-m-d');
            } else {
                $this->startDate = $startDate; // Keep as is if format doesn't match
            }
        }
        
        if ($endDate) {
            $dateObj = DateTime::createFromFormat('d-m-Y', $endDate);
            if ($dateObj) {
                $this->endDate = $dateObj->format('Y-m-d');
            } else {
                $this->endDate = $endDate; // Keep as is if format doesn't match
            }
        }
        
        $this->user = Auth::user();
    }

    public function collection()
    {
        $query = SaleModel::query();

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('sale_date', [$this->startDate, $this->endDate]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'SP Number',
            'TBS Quantity',
            'KG Quantity',
            'Price per KG',
            'Total Amount',
            'Tax Amount',
            'Total with Tax',
            'Sale Date',
            'Customer Name',
            'Customer Address',
            'Created At',
            'Updated At',
        ];
    }

    public function map($sale): array
    {
        return [
            $sale->id,
            $sale->sp_number,
            $sale->tbs_quantity,
            $sale->kg_quantity,
            $sale->price_per_kg,
            $sale->total_amount,
            $sale->tax_amount,
            $sale->total_with_tax,
            $sale->sale_date->format('d-m-Y'),
            $sale->customer_name,
            $sale->customer_address,
            $sale->created_at->format('d-m-Y H:i:s'),
            $sale->updated_at->format('d-m-Y H:i:s'),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Add header information
                $event->sheet->setCellValue('A1', 'Sales Data Export');
                $event->sheet->setCellValue('A2', 'Exported by: ' . ($this->user ? $this->user->name : 'System'));
                $event->sheet->setCellValue('A3', 'Exported on: ' . now()->format('Y-m-d H:i:s'));
                
                if ($this->startDate && $this->endDate) {
                    $event->sheet->setCellValue('A4', 'Date Range: ' . $this->startDate . ' to ' . $this->endDate);
                }

                // Style the header
                $event->sheet->getStyle('A1:A4')->getFont()->setBold(true);
                $event->sheet->getStyle('A1:A4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            },
        ];
    }
}