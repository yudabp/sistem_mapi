<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ekspor Data Produksi</title>
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
            <div>Laporan Data Produksi</div>
        </div>
    </div>

    <div class="document-title">
        Ekspor Data Produksi
    </div>

    <div class="export-info">
        <p><strong>Diekspor oleh:</strong> <span class="accent">{{ $exportInfo['exportedBy'] }}</span></p>
        <p><strong>Diekspor pada:</strong> {{ $exportInfo['exportedOn'] }}</p>
        @if($exportInfo['startDate'] && $exportInfo['endDate'])
            <p><strong>Rentang Tanggal:</strong> {{ $exportInfo['startDate'] }} hingga {{ $exportInfo['endDate'] }}</p>
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
                    <td class="text-right">{{ number_format($production->tbs_quantity, 2) }}</td>
                    <td class="text-right">{{ number_format($production->kg_quantity, 2) }}</td>
                    <td>{{ $production->divisionRel ? $production->divisionRel->name : $production->division }}</td>
                    <td>{{ $production->pksRel ? $production->pksRel->name : $production->pks }}</td>
                    <td>{{ $production->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $production->updated_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">No production records found</td>
                </tr>
            @endforelse
            <!-- Total Row -->
            @if(count($productions) > 0)
                <tr class="total-row">
                    <td colspan="5"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong>{{ number_format($productions->sum('tbs_quantity'), 2) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($productions->sum('kg_quantity'), 2) }}</strong></td>
                    <td colspan="4"></td> <!-- Empty cells for remaining columns -->
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Dibuat pada {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>