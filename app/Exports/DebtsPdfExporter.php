<?php

namespace App\Exports;

use App\Models\Debt as DebtModel;
use Illuminate\Support\Facades\Auth;
use DateTime;

class DebtsPdfExporter
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
        $query = DebtModel::query();

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('due_date', [$this->startDate, $this->endDate]);
        }

        $debts = $query->get();
        
        $exportInfo = [
            'exportedBy' => $this->user ? $this->user->name : 'System',
            'exportedOn' => now()->format('Y-m-d H:i:s'),
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ];

        // Create simple HTML content without external dependencies
        $html = '<!DOCTYPE html>';
        $html .= '<html>';
        $html .= '<head>';
        $html .= '<meta charset="UTF-8">';
        $html .= '<title>Debt Data Export</title>';
        $html .= '<style>';
        $html .= 'body { font-family: \'DejaVu Sans\', sans-serif; font-size: 12px; margin: 20px; background-color: #f8fff8; }';
        $html .= '.header-container { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #22c55e; }';
        $html .= '.logo { width: 80px; height: auto; }';
        $html .= '.company-info { text-align: right; }';
        $html .= '.company-name { font-size: 18px; font-weight: bold; color: #166534; margin-bottom: 5px; }';
        $html .= '.document-title { font-size: 20px; font-weight: bold; color: #166534; text-align: center; margin: 20px 0; padding: 10px; background-color: #dcfce7; border-radius: 8px; border: 1px solid #bbf7d0; }';
        $html .= '.export-info { margin-bottom: 20px; line-height: 1.5; background-color: #f0fdf4; padding: 15px; border-radius: 6px; border: 1px solid #bbf7d0; }';
        $html .= '.export-info p { margin: 5px 0; }';
        $html .= 'table { width: 100%; border-collapse: collapse; margin-top: 20px; }';
        $html .= 'th, td { border: 1px solid #22c55e; padding: 8px; text-align: left; }';
        $html .= 'th { background-color: #bbf7d0; font-weight: bold; color: #166534; }';
        $html .= 'tr:nth-child(even) { background-color: #f0fdf4; }';
        $html .= '.text-right { text-align: right; }';
        $html .= '.text-center { text-align: center; }';
        $html .= '.footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; padding-top: 15px; border-top: 1px solid #bbf7d0; }';
        $html .= '.total-row { background-color: #dcfce7 !important; font-weight: bold; }';
        $html .= '.accent { color: #eab308; }';
        $html .= '@page { size: A4 landscape; margin: 20mm; }';
        $html .= '@media print { @page { size: A4 landscape; } }';
        $html .= '</style>';
        $html .= '</head>';
        $html .= '<body>';

        $html .= '<div class="header-container">';
        $html .= '<img src="' . public_path('images/main-logo.png') . '" alt="Company Logo" class="logo">';
        $html .= '<div class="company-info">';
        $html .= '<div class="company-name">PT. Agro Palma Indonesia</div>';
        $html .= '<div>Laporan Data Hutang</div>';
        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div class="document-title">';
        $html .= 'Debt Data Export';
        $html .= '</div>';

        $html .= '<div class="export-info">';
        $html .= '<p><strong>Exported by:</strong> <span class="accent">' . htmlspecialchars($exportInfo['exportedBy']) . '</span></p>';
        $html .= '<p><strong>Exported on:</strong> ' . htmlspecialchars($exportInfo['exportedOn']) . '</p>';
        if ($exportInfo['startDate'] && $exportInfo['endDate']) {
            $html .= '<p><strong>Date Range:</strong> ' . htmlspecialchars($exportInfo['startDate']) . ' to ' . htmlspecialchars($exportInfo['endDate']) . '</p>';
        }
        $html .= '</div>';

        $html .= '<table>';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>ID</th>';
        $html .= '<th>Amount</th>';
        $html .= '<th>Creditor</th>';
        $html .= '<th>Due Date</th>';
        $html .= '<th>Description</th>';
        $html .= '<th>Status</th>';
        $html .= '<th>Paid Date</th>';
        $html .= '<th>Created At</th>';
        $html .= '<th>Updated At</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        // Initialize totals
        $totalAmount = 0;

        foreach ($debts as $debt) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($debt->id) . '</td>';
            $html .= '<td class="text-right">' . number_format($debt->amount, 2) . '</td>';
            $html .= '<td>' . htmlspecialchars($debt->creditor) . '</td>';
            $html .= '<td>' . htmlspecialchars($debt->due_date->format('Y-m-d')) . '</td>';
            $html .= '<td>' . htmlspecialchars($debt->description) . '</td>';
            $html .= '<td>' . htmlspecialchars($debt->status) . '</td>';
            $html .= '<td>' . htmlspecialchars($debt->paid_date ? $debt->paid_date->format('Y-m-d') : '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($debt->created_at->format('Y-m-d H:i:s')) . '</td>';
            $html .= '<td>' . htmlspecialchars($debt->updated_at->format('Y-m-d H:i:s')) . '</td>';
            $html .= '</tr>';
            
            // Add to totals
            $totalAmount += $debt->amount;
        }

        // Add total row
        $html .= '<tr class="total-row">';
        $html .= '<td><strong>TOTAL</strong></td>';
        $html .= '<td class="text-right"><strong>' . number_format($totalAmount, 2) . '</strong></td>';
        $html .= '<td colspan="7"></td>'; // Empty cells for the remaining columns
        $html .= '</tr>';

        $html .= '</tbody>';
        $html .= '</table>';

        $html .= '<div class="footer">';
        $html .= '<p>Total Records: ' . count($debts) . '</p>';
        $html .= '<p>Generated on ' . now()->format('Y-m-d H:i:s') . '</p>';
        $html .= '</div>';

        $html .= '</body>';
        $html .= '</html>';

        // Ensure proper UTF-8 encoding
        $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
        
        return $html;
    }
}