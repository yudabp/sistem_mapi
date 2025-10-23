<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Production Data Export</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
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
        <h1>Production Data Export</h1>
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
                <th>SP Number</th>
                <th>Vehicle Number</th>
                <th>TBS Quantity (KG)</th>
                <th>KG Quantity</th>
                <th>Division</th>
                <th>PKS</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productions as $production)
                <tr>
                    <td>{{ $production->id }}</td>
                    <td>{{ $production->transaction_number }}</td>
                    <td>{{ $production->date->format('Y-m-d') }}</td>
                    <td>{{ $production->sp_number }}</td>
                    <td>{{ $production->vehicle_number }}</td>
                    <td>{{ number_format($production->tbs_quantity, 2) }}</td>
                    <td>{{ number_format($production->kg_quantity, 2) }}</td>
                    <td>{{ $production->divisionRel ? $production->divisionRel->name : $production->division }}</td>
                    <td>{{ $production->pks }}</td>
                    <td>{{ $production->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $production->updated_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align: center;">No production records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>