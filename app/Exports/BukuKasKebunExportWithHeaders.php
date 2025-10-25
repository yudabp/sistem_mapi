<?php

namespace App\Exports;

use App\Models\BukuKasKebun;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\Auth;
use DateTime;

class BukuKasKebunExportWithHeaders implements FromCollection, WithHeadings, WithMapping, WithEvents
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
        $query = BukuKasKebun::query();

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('transaction_date', [$this->startDate, $this->endDate]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Transaction Number',
            'Transaction Date',
            'Transaction Type',
            'Amount',
            'Source/Destination',
            'Received By',
            'Notes',
            'Category',
            'Expense Category',
            'Debt ID',
            'KP ID',
            'Created At',
            'Updated At',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->transaction_number,
            $transaction->transaction_date->format('d-m-Y'),
            $transaction->transaction_type,
            $transaction->amount,
            $transaction->source_destination,
            $transaction->received_by,
            $transaction->notes,
            $transaction->category,
            $transaction->expenseCategory ? $transaction->expenseCategory->name : null, // Get expense category name if available
            $transaction->debt_id,
            $transaction->kp_id,
            $transaction->created_at->format('d-m-Y H:i:s'),
            $transaction->updated_at->format('d-m-Y H:i:s'),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Add header information
                $event->sheet->setCellValue('A1', 'Buku Kas Kebun Data Export');
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