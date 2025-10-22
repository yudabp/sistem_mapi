<?php

namespace App\Exports;

use App\Models\Debt as DebtModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\Auth;
use DateTime;

class DebtsExportWithHeaders implements FromCollection, WithHeadings, WithMapping, WithEvents
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
        $query = DebtModel::query();

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('due_date', [$this->startDate, $this->endDate]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Amount',
            'Creditor',
            'Due Date',
            'Description',
            'Status',
            'Paid Date',
            'Created At',
            'Updated At',
        ];
    }

    public function map($debt): array
    {
        return [
            $debt->id,
            $debt->amount,
            $debt->creditor,
            $debt->due_date->format('d-m-Y'),
            $debt->description,
            $debt->status,
            $debt->paid_date ? $debt->paid_date->format('d-m-Y') : null,
            $debt->created_at->format('d-m-Y H:i:s'),
            $debt->updated_at->format('d-m-Y H:i:s'),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Add header information
                $event->sheet->setCellValue('A1', 'Debt Data Export');
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