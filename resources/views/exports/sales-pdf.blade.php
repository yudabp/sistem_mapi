<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sales Data Export</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            margin: 20px;
            background-color: #f8fff8; /* Light green background */
        }
        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #22c55e; /* Green border */
        }
        .logo {
            width: 80px;
            height: auto;
        }
        .company-info {
            text-align: right;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #166534; /* Dark green */
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 20px;
            font-weight: bold;
            color: #166534; /* Dark green */
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background-color: #dcfce7; /* Light green */
            border-radius: 8px;
            border: 1px solid #bbf7d0; /* Light green border */
        }
        .export-info {
            margin-bottom: 20px;
            line-height: 1.5;
            background-color: #f0fdf4; /* Very light green */
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #bbf7d0; /* Light green border */
        }
        .export-info p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #22c55e; /* Green border */
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #bbf7d0; /* Light green header */
            font-weight: bold;
            color: #166534; /* Dark green text */
        }
        tr:nth-child(even) {
            background-color: #f0fdf4; /* Very light green alternating rows */
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            padding-top: 15px;
            border-top: 1px solid #bbf7d0; /* Light green border */
        }
        .total-row {
            background-color: #dcfce7 !important; /* Light green for total row */
            font-weight: bold;
        }
        .accent {
            color: #eab308; /* Yellow accent */
        }
    </style>
</head>
<body>
    <div class="header-container">
        <img src="{{ public_path('images/main-logo.png') }}" alt="Company Logo" class="logo">
        <div class="company-info">
            <div class="company-name">PT. Agro Palma Indonesia</div>
            <div>Laporan Data Penjualan</div>
        </div>
    </div>

    <div class="document-title">
        Sales Data Export
    </div>

    <div class="export-info">
        <p><strong>Exported by:</strong> <span class="accent">{{ $exportInfo['exportedBy'] }}</span></p>
        <p><strong>Exported on:</strong> {{ $exportInfo['exportedOn'] }}</p>
        @if($exportInfo['startDate'] && $exportInfo['endDate'])
            <p><strong>Date Range:</strong> {{ $exportInfo['startDate'] }} to {{ $exportInfo['endDate'] }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>SP Number</th>
                <th>TBS Quantity</th>
                <th>KG Quantity</th>
                <th>Price per KG</th>
                <th>Total Amount</th>
                <th>Sale Date</th>
                <th>Customer Name</th>
                <th>Customer Address</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td>{{ $sale->id }}</td>
                    <td>{{ $sale->sp_number }}</td>
                    <td class="text-right">{{ number_format($sale->tbs_quantity, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($sale->kg_quantity, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($sale->price_per_kg, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($sale->total_amount, 2, ',', '.') }}</td>
                    <td>{{ $sale->sale_date->format('d-m-Y') }}</td>
                    <td>{{ e($sale->customer_name) }}</td>
                    <td>{{ e($sale->customer_address) }}</td>
                    <td>{{ $sale->created_at->format('d-m-Y H:i:s') }}</td>
                    <td>{{ $sale->updated_at->format('d-m-Y H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align: center;">No sales records found</td>
                </tr>
            @endforelse
            <!-- Total Row -->
            @if(count($sales) > 0)
                <tr class="total-row">
                    <td colspan="2"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong>{{ number_format($sales->sum('tbs_quantity'), 2, ',', '.') }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($sales->sum('kg_quantity'), 2, ',', '.') }}</strong></td>
                    <td></td> <!-- Empty for price per kg -->
                    <td class="text-right"><strong>{{ number_format($sales->sum('total_amount'), 2, ',', '.') }}</strong></td>
                    <td colspan="5"></td> <!-- Empty cells for remaining columns -->
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>