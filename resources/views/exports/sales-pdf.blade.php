<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sales Data Export</title>
    <style>
        /* UTF-8 Support for Indonesian characters */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .export-info {
            margin-bottom: 20px;
            line-height: 1.5;
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
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sales Data Export</h1>
    </div>

    <div class="export-info">
        <p><strong>Exported by:</strong> {{ $exportInfo['exportedBy'] }}</p>
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
                    <td>{{ number_format($sale->tbs_quantity, 2, ',', '.') }}</td>
                    <td>{{ number_format($sale->kg_quantity, 2, ',', '.') }}</td>
                    <td>{{ number_format($sale->price_per_kg, 2, ',', '.') }}</td>
                    <td>{{ number_format($sale->total_amount, 2, ',', '.') }}</td>
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
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>