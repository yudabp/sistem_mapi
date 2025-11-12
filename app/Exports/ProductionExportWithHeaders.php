<?php

namespace App\Exports;

use App\Models\Production as ProductionModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Support\Facades\Auth;
use DateTime;

class ProductionExportWithHeaders implements FromCollection, WithHeadings, WithMapping, WithEvents
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
        $query = ProductionModel::query();

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('date', [$this->startDate, $this->endDate]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Transaction Number',
            'Date',
            'SP Number',
            'Vehicle Number',
            'TBS Quantity (KG)',
            'KG Quantity',
            'Division',
            'PKS',
            'Created At',
            'Updated At',
        ];
    }

    public function map($production): array
    {
        return [
            $production->id,
            $production->transaction_number,
            $production->date->format('d-m-Y'),
            $production->sp_number,
            $production->vehicle_number, // Uses the accessor method which handles both old and new structures
            $production->tbs_quantity,
            $production->kg_quantity,
            $production->division_name, // Uses the accessor method which handles both old and new structures
            $production->pks_name, // Uses the accessor method which handles both old and new structures
            $production->created_at->format('d-m-Y H:i:s'),
            $production->updated_at->format('d-m-Y H:i:s'),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Add header information
                $event->sheet->setCellValue('A1', 'Production Data Export');
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