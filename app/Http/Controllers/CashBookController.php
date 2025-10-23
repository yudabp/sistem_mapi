<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\CashBookPdfExporter;
use Barryvdh\DomPDF\Facade\Pdf;

class CashBookController extends Controller
{
    public function exportPdf(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        
        // Create the PDF exporter
        $exporter = new CashBookPdfExporter($startDate, $endDate);
        $html = $exporter->generate();

        // Ensure proper UTF-8 encoding
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        
        $filename = 'cash_book_data_export_' . now()->format('Y-m-d_H-i-s') . '.pdf';

        // Create DomPDF instance with proper UTF-8 configuration
        $options = new \Dompdf\Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', false);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('enable_font_subsetting', true);
        $options->setChroot(__DIR__ . '/../../../');
        
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response()->make($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}