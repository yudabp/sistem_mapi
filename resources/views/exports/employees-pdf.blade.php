<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Employee Data Export</title>
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
        <h1>Employee Data Export</h1>
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
                <th>NDP (Employee ID)</th>
                <th>Name</th>
                <th>Department</th>
                <th>Position</th>
                <th>Grade</th>
                <th>Family Composition</th>
                <th>Monthly Salary</th>
                <th>Status</th>
                <th>Hire Date</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $employee)
                <tr>
                    <td>{{ $employee->id }}</td>
                    <td>{{ $employee->ndp }}</td>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->department }}</td>
                    <td>{{ $employee->position }}</td>
                    <td>{{ $employee->grade }}</td>
                    <td>{{ $employee->family_composition }}</td>
                    <td>{{ number_format($employee->monthly_salary, 2) }}</td>
                    <td>{{ ucfirst($employee->status) }}</td>
                    <td>{{ $employee->hire_date->format('Y-m-d') }}</td>
                    <td>{{ $employee->address }}</td>
                    <td>{{ $employee->phone }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $employee->updated_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="15" style="text-align: center;">No employee records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>