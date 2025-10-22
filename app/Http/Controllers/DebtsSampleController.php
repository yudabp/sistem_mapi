<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DebtsSampleController extends Controller
{
    public function downloadSample()
    {
        // Sample data for debts
        $sampleData = [
            ['amount', 'creditor', 'due_date', 'description', 'status', 'paid_date'],
            ['5000000', 'PT Supplier Sawit', date('d-m-Y', strtotime('+30 days')), 'Pembelian pupuk untuk kebun', 'unpaid', ''],
            ['2500000', 'CV Transport Berkah', date('d-m-Y', strtotime('+15 days')), 'Biaya transport TBS bulan ini', 'unpaid', ''],
            ['1500000', 'Bpk. Ali Karyawan', date('d-m-Y', strtotime('+7 days')), 'Pinjaman karyawan', 'unpaid', ''],
        ];
        
        $csv = '';
        foreach ($sampleData as $row) {
            $csv .= '"' . implode('","', $row) . "\"\n";
        }
        
        $filename = 'sample_debts_data.csv';
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}