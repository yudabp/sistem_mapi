<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductionSampleController extends Controller
{
    public function downloadSample()
    {
        // Sample data for production
        $sampleData = [
            ['transaction_number', 'date', 'sp_number', 'vehicle_number', 'tbs_quantity', 'kg_quantity', 'division', 'pks'],
            ['TRX001', date('Y-m-d'), 'SP001', 'B1234XYZ', '1000.50', '950.20', 'Afdeling A', 'PKS 1'],
            ['TRX002', date('Y-m-d'), 'SP002', 'B5678XYZ', '1200.75', '1140.80', 'Afdeling B', 'PKS 2'],
            ['TRX003', date('Y-m-d'), 'SP003', 'B9012XYZ', '950.25', '902.75', 'Afdeling C', 'PKS 3'],
        ];
        
        $csv = '';
        foreach ($sampleData as $row) {
            $csv .= '"' . implode('","', $row) . "\"\n";
        }
        
        $filename = 'sample_production_data.csv';
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
