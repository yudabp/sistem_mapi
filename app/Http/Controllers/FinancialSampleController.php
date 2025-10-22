<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinancialSampleController extends Controller
{
    public function downloadSample()
    {
        // Sample data for financial
        $sampleData = [
            ['transaction_date', 'transaction_type', 'amount', 'source_destination', 'received_by', 'notes', 'category'],
            [date('d-m-Y'), 'income', '5000000', 'Penjualan TBS', 'Bpk. Andi', 'Pembayaran penjualan TBS minggu ini', 'Sales'],
            [date('d-m-Y'), 'expense', '2500000', 'Biaya Transport', 'Bpk. Budi', 'Biaya angkut TBS ke PKS', 'Transport'],
            [date('d-m-Y'), 'expense', '1500000', 'Gaji Karyawan', 'Ibu Cinta', 'Gaji bulanan karyawan', 'Salary'],
        ];
        
        $csv = '';
        foreach ($sampleData as $row) {
            $csv .= '"' . implode('","', $row) . "\"\n";
        }
        
        $filename = 'sample_financial_data.csv';
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}