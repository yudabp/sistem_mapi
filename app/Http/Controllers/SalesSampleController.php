<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalesSampleController extends Controller
{
    public function downloadSample()
    {
        // Sample data for sales
        $sampleData = [
            ['sp_number', 'tbs_quantity', 'kg_quantity', 'price_per_kg', 'total_amount', 'sale_date', 'customer_name', 'customer_address'],
            ['SP001', '1000.50', '950.20', '2500.00', '2375500.00', date('Y-m-d'), 'PT Sawit Makmur', 'Jl. Raya Sawit No. 123, Medan'],
            ['SP002', '1200.75', '1140.80', '2500.00', '2852000.00', date('Y-m-d'), 'CV Minyak Nabati', 'Jl. Kemiri Raya No. 45, Jakarta'],
            ['SP003', '950.25', '902.75', '2500.00', '2256875.00', date('Y-m-d'), 'PT Kelapa Sawit Indonesia', 'Jl. Tanjung Morawa No. 78, Sumatera Utara'],
        ];
        
        $csv = '';
        foreach ($sampleData as $row) {
            $csv .= '"' . implode('","', $row) . "\"\n";
        }
        
        $filename = 'sample_sales_data.csv';
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}