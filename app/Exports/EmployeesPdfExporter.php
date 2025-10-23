<?php

namespace App\Exports;

use App\Models\Employee as EmployeeModel;
use Illuminate\Support\Facades\Auth;
use DateTime;

class EmployeesPdfExporter
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
        $query = EmployeeModel::query();

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('hire_date', [$this->startDate, $this->endDate]);
        }

        $employees = $query->get();
        
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
        $html .= '<title>Employee Data Export</title>';
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
        $html .= '<div>Laporan Data Karyawan</div>';
        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div class="document-title">';
        $html .= 'Employee Data Export';
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
        $html .= '<th>NDP (Employee ID)</th>';
        $html .= '<th>Name</th>';
        $html .= '<th>Department</th>';
        $html .= '<th>Position</th>';
        $html .= '<th>Grade</th>';
        $html .= '<th>Family Composition</th>';
        $html .= '<th>Monthly Salary</th>';
        $html .= '<th>Status</th>';
        $html .= '<th>Hire Date</th>';
        $html .= '<th>Address</th>';
        $html .= '<th>Phone</th>';
        $html .= '<th>Email</th>';
        $html .= '<th>Created At</th>';
        $html .= '<th>Updated At</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        // Initialize totals
        $totalSalary = 0;

        foreach ($employees as $employee) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($employee->id) . '</td>';
            $html .= '<td>' . htmlspecialchars($employee->ndp) . '</td>';
            $html .= '<td>' . htmlspecialchars($employee->name) . '</td>';
            $html .= '<td>' . htmlspecialchars($employee->departmentRel ? $employee->departmentRel->name : $employee->department) . '</td>';
            $html .= '<td>' . htmlspecialchars($employee->positionRel ? $employee->positionRel->name : $employee->position) . '</td>';
            $html .= '<td>' . htmlspecialchars($employee->grade) . '</td>';
            $html .= '<td>' . htmlspecialchars($employee->familyCompositionRel ? $employee->familyCompositionRel->number : $employee->family_composition) . '</td>';
            $html .= '<td class="text-right">' . number_format($employee->monthly_salary, 2) . '</td>';
            $html .= '<td>' . htmlspecialchars(ucfirst($employee->status)) . '</td>';
            $html .= '<td>' . htmlspecialchars($employee->hire_date->format('Y-m-d')) . '</td>';
            $html .= '<td>' . htmlspecialchars($employee->address) . '</td>';
            $html .= '<td>' . htmlspecialchars($employee->phone) . '</td>';
            $html .= '<td>' . htmlspecialchars($employee->email) . '</td>';
            $html .= '<td>' . htmlspecialchars($employee->created_at->format('Y-m-d H:i:s')) . '</td>';
            $html .= '<td>' . htmlspecialchars($employee->updated_at->format('Y-m-d H:i:s')) . '</td>';
            $html .= '</tr>';
            
            // Add to totals
            $totalSalary += $employee->monthly_salary;
        }

        // Add total row
        $html .= '<tr class="total-row">';
        $html .= '<td colspan="7"><strong>TOTAL</strong></td>';
        $html .= '<td class="text-right"><strong>' . number_format($totalSalary, 2) . '</strong></td>';
        $html .= '<td colspan="7"></td>'; // Empty cells for the remaining columns
        $html .= '</tr>';

        $html .= '</tbody>';
        $html .= '</table>';

        $html .= '<div class="footer">';
        $html .= '<p>Total Records: ' . count($employees) . '</p>';
        $html .= '<p>Generated on ' . now()->format('Y-m-d H:i:s') . '</p>';
        $html .= '</div>';

        $html .= '</body>';
        $html .= '</html>';

        // Ensure proper UTF-8 encoding
        $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
        
        return $html;
    }
}