<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinancialSampleController extends Controller
{
    public function downloadSample()
    {
        // Sample data for financial - Updated to match current table structure
        // Categories will be used to determine routing to KP or BKK tables by the import
        $sampleData = [
            ['transaction_date', 'transaction_type', 'amount', 'source_destination', 'received_by', 'notes', 'category'],
            [date('Y-m-d'), 'income', '15000000', 'Customer Payment', 'Budi Santoso', 'Pembayaran penjualan bulan ini', 'Sales Revenue'],
            [date('Y-m-d'), 'expense', '5000000', 'Supplier', 'Siti Aminah', 'Pembelian bahan baku', 'Operational Cost'],
            [date('Y-m-d'), 'expense', '3000000', 'Transport Company', 'Ahmad Fauzi', 'Biaya transportasi', 'Transportation Cost'],
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