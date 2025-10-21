<?php

namespace App\Http\Controllers;

use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    /**
     * Export sales data to Excel
     */
    public function export(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $filename = 'sales_data_' . $filter . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new SalesExport($filter), $filename);
    }
}