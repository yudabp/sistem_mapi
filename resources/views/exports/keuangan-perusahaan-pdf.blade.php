<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Keuangan Perusahaan Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .date-range {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Keuangan Perusahaan Report</h1>
        @if(isset($startDate) && isset($endDate))
        <div class="date-range">
            <strong>Date Range:</strong> {{ $startDate }} to {{ $endDate }}
        </div>
        @endif
        <div>Generated on: {{ now()->format('Y-m-d H:i:s') }}</div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Transaction Number</th>
                <th>Transaction Date</th>
                <th>Transaction Type</th>
                <th>Amount</th>
                <th>Source/Destination</th>
                <th>Received By</th>
                <th>Notes</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->transaction_number }}</td>
                <td>{{ $item->transaction_date->format('d-m-Y') }}</td>
                <td>{{ $item->transaction_type }}</td>
                <td>{{ number_format($item->amount, 2) }}</td>
                <td>{{ $item->source_destination }}</td>
                <td>{{ $item->received_by ?? '-' }}</td>
                <td>{{ $item->notes ?? '-' }}</td>
                <td>{{ $item->category }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>