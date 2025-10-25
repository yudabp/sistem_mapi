<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeesSampleController extends Controller
{
    public function downloadSample()
    {
        // Sample data for employees
        $sampleData = [
            ['ndp', 'name', 'department', 'position', 'grade', 'family_composition', 'monthly_salary', 'status', 'hire_date', 'address', 'phone', 'email'],
            ['NDP001', 'Andi Prasetyo', 'Afdeling 1', 'Mandor', 'B', '3', '3500000', 'active', date('Y-m-d', strtotime('-2 years')), 'Jl. Merdeka No. 1, Medan', '081234567890', 'andi@example.com'],
            ['NDP002', 'Budi Santoso', 'Afdeling 2', 'Kepala Kebun', 'A', '4', '5000000', 'active', date('Y-m-d', strtotime('-3 years')), 'Jl. Sudirman No. 2, Medan', '081234567891', 'budi@example.com'],
            ['NDP003', 'Cinta Dewi', 'Administrasi', 'Staff Administrasi', 'C', '2', '2500000', 'active', date('Y-m-d', strtotime('-1 year')), 'Jl. Thamrin No. 3, Medan', '081234567892', 'cinta@example.com'],
        ];
        
        $csv = '';
        foreach ($sampleData as $row) {
            $csv .= '"' . implode('","', $row) . "\"\n";
        }
        
        $filename = 'sample_employees_data.csv';
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}