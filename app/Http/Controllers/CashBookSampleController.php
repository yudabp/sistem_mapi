<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CashBookSampleController extends Controller
{
    public function downloadSample()
    {
        // Sample data for cash book - Updated to match current table structure
        $sampleData = [
            ['transaction_date', 'transaction_type', 'amount', 'purpose', 'notes', 'category'],
            [date('Y-m-d'), 'expense', '2500000', 'Fuel Purchase', 'Bensin kendaraan operasional', 'Transportation Cost'],
            [date('Y-m-d'), 'expense', '1800000', 'Fertilizer Purchase', 'Pembelian pupuk organik', 'Fertilizer Cost'],
            [date('Y-m-d'), 'income', '5000000', 'Petty Cash Return', 'Pengembalian dana petty cash', 'Other Income'],
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