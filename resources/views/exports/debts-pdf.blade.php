<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Debt Data Export</title>
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
        <h1>Debt Data Export</h1>
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
                <th>Amount</th>
                <th>Creditor</th>
                <th>Due Date</th>
                <th>Description</th>
                <th>Status</th>
                <th>Paid Date</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($debts as $debt)
                <tr>
                    <td>{{ $debt->id }}</td>
                    <td>{{ number_format($debt->amount, 2) }}</td>
                    <td>{{ $debt->creditor }}</td>
                    <td>{{ $debt->due_date->format('Y-m-d') }}</td>
                    <td>{{ $debt->description }}</td>
                    <td>{{ $debt->status }}</td>
                    <td>{{ $debt->paid_date ? $debt->paid_date->format('Y-m-d') : '-' }}</td>
                    <td>{{ $debt->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $debt->updated_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">No debt records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>