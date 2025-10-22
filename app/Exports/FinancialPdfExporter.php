<?php

namespace App\Exports;

use App\Models\FinancialTransaction as FinancialTransactionModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use DateTime;

class FinancialPdfExporter
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

    public function generate()
    {
        $query = FinancialTransactionModel::query();

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('transaction_date', [$this->startDate, $this->endDate]);
        }

        $transactions = $query->get();
        
        $exportInfo = [
            'exportedBy' => $this->user ? $this->user->name : 'System',
            'exportedOn' => now()->format('Y-m-d H:i:s'),
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ];

        // Render the view
        $html = View::make('exports.financial-pdf', [
            'transactions' => $transactions,
            'exportInfo' => $exportInfo
        ])->render();
        
        // Clean the HTML to ensure proper UTF-8 encoding
        $html = $this->cleanHtmlForPdf($html);
        
        return $html;
    }
    
    /**
     * Clean HTML content for PDF generation
     */
    private function cleanHtmlForPdf($html)
    {
        // Remove any invalid UTF-8 characters
        $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
        
        // Replace any problematic characters
        $html = str_replace(chr(0xC2).chr(0xA0), ' ', $html); // Replace non-breaking spaces
        
        // Ensure all special characters are properly encoded
        if (function_exists('iconv')) {
            $html = iconv('UTF-8', 'UTF-8//IGNORE', $html);
        }
        
        return $html;
    }
}