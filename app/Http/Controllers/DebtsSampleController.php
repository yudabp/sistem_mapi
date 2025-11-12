<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DebtsSampleController extends Controller
{
    public function downloadSample()
    {
        // Sample data for debts - Updated to match current table structure
        $sampleData = [
            ['amount', 'sisa_hutang', 'cicilan_per_bulan', 'creditor', 'debt_type', 'due_date', 'description', 'status', 'paid_date'],
            ['50000000', '50000000', '10000000', 'Bank Mandiri', 'Hutang Bank', date('Y-m-d', strtotime('+30 days')), 'Pembelian alat produksi', 'unpaid', ''],
            ['25000000', '15000000', '5000000', 'PT Supplier', 'Hutang Supplier', date('Y-m-d', strtotime('+15 days')), 'Pembelian bahan baku', 'unpaid', ''],
            ['15000000', '0', '0', 'Koperasi Karyawan', 'Hutang Karyawan', date('Y-m-d', strtotime('+7 days')), 'Pinjaman karyawan', 'paid', date('Y-m-d')],
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