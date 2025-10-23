<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Debt Data Export</title>
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
            <div>Laporan Data Hutang</div>
        </div>
    </div>

    <div class="document-title">
        Debt Data Export
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
                    <td class="text-right">{{ number_format($debt->amount, 2) }}</td>
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
            <!-- Total Row -->
            @if(count($debts) > 0)
                <tr class="total-row">
                    <td><strong>TOTAL</strong></td>
                    <td class="text-right"><strong>{{ number_format($debts->sum('amount'), 2) }}</strong></td>
                    <td colspan="7"></td> <!-- Empty cells for remaining columns -->
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>