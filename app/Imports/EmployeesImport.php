<?php

namespace App\Imports;

use App\Models\Employee as EmployeeModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeesImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new EmployeeModel([
            'ndp' => $row['ndp'] ?? null,
            'name' => $row['name'] ?? null,
            'department' => $row['department'] ?? null,
            'position' => $row['position'] ?? null,
            'grade' => $row['grade'] ?? null,
            'family_composition' => $row['family_composition'] ?? 0,
            'monthly_salary' => $row['monthly_salary'] ?? null,
            'status' => $row['status'] ?? 'active',
            'hire_date' => $row['hire_date'] ?? null,
            'address' => $row['address'] ?? null,
            'phone' => $row['phone'] ?? null,
            'email' => $row['email'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'ndp' => 'required|unique:employees,ndp',
            'name' => 'required',
            'department' => 'required',
            'position' => 'required',
            'monthly_salary' => 'required|numeric',
            'hire_date' => 'required|date',
            'status' => 'required',
        ];
    }
}