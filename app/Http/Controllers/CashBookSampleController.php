<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CashBookSampleController extends Controller
{
    public function downloadSample()
    {
        // Sample data for cash book
        $sampleData = [
            ['transaction_date', 'transaction_type', 'amount', 'purpose', 'description', 'notes'],
            [date('d-m-Y'), 'income', '5000000', 'Penjualan TBS', 'Pembayaran penjualan TBS minggu ini', 'Diterima oleh Bpk. Andi'],
            [date('d-m-Y'), 'expense', '2500000', 'Biaya Transport', 'Biaya angkut TBS ke PKS', 'Dibayar oleh Bpk. Budi'],
            [date('d-m-Y'), 'expense', '1500000', 'Gaji Karyawan', 'Gaji bulanan karyawan', 'Dibayar oleh Ibu Cinta'],
        ];
        
        $csv = '';
        foreach ($sampleData as $row) {
            $csv .= '"' . implode('","', $row) . "\"\n";
        }
        
        $filename = 'sample_cashbook_data.csv';
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}