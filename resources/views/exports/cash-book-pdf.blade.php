<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cash Book Data Export</title>
    <style>
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
        <h1>Cash Book Data Export</h1>
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
                <th>Transaction Number</th>
                <th>Date</th>
                <th>Transaction Type</th>
                <th>Amount</th>
                <th>Purpose</th>
                <th>Notes</th>
                <th>Category</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->transaction_number }}</td>
                    <td>{{ $transaction->transaction_date->format('Y-m-d') }}</td>
                    <td>{{ ucfirst($transaction->transaction_type) }}</td>
                    <td>{{ number_format($transaction->amount, 2) }}</td>
                    <td>{{ $transaction->source_destination }}</td>
                    <td>{{ $transaction->notes }}</td>
                    <td>{{ $transaction->category }}</td>
                    <td>{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $transaction->updated_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center;">No cash book transactions found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>