<?php

namespace App\Imports;

use App\Models\Employee as EmployeeModel;
use App\Models\Department;
use App\Models\Position;
use App\Models\FamilyComposition;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class EmployeesImport implements ToModel, WithHeadingRow, WithValidation
{
    private function parseFlexibleDate($dateValue)
    {
        if (empty($dateValue)) {
            return null;
        }
        
        try {
            // Try Carbon's default parsing first
            return Carbon::parse($dateValue)->format('Y-m-d');
        } catch (\Exception $e) {
            // If that fails, try common ambiguous formats
            // Handle MM/DD/YYYY or DD/MM/YYYY formats
            if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $dateValue, $matches)) {
                $part1 = (int)$matches[1];
                $part2 = (int)$matches[2];
                $year = (int)$matches[3];
                
                // Try assuming first part is month
                if ($part1 >= 1 && $part1 <= 12) {
                    try {
                        return Carbon::createFromDate($year, $part1, $part2)->format('Y-m-d');
                    } catch (\Exception $e) {
                        // Continue to next attempt
                    }
                }
                
                // Try assuming second part is month
                if ($part2 >= 1 && $part2 <= 12) {
                    try {
                        return Carbon::createFromDate($year, $part2, $part1)->format('Y-m-d');
                    } catch (\Exception $e) {
                        // Continue to next attempt
                    }
                }
            }
        }
        
        // If all parsing attempts fail, return null
        return null;
    }

    public function model(array $row)
    {
        // Find or create department_id
        $department = null;
        if (!empty($row['department'])) {
            $department = Department::firstOrCreate(
                ['name' => $row['department']],
                [
                    'name' => $row['department'],
                    'description' => 'Auto-created from import',
                    'is_active' => true,
                ]
            );
        }

        // Find or create position_id
        $position = null;
        if (!empty($row['position'])) {
            $position = Position::firstOrCreate(
                ['name' => $row['position']],
                [
                    'name' => $row['position'],
                    'description' => 'Auto-created from import',
                    'is_active' => true,
                ]
            );
        }

        // Find or create family_composition_id
        $familyComposition = null;
        if (!empty($row['family_composition'])) {
            $familyComposition = FamilyComposition::firstOrCreate(
                ['number' => $row['family_composition']],
                [
                    'number' => $row['family_composition'],
                    'description' => 'Auto-created from import',
                    'is_active' => true,
                ]
            );
        }

        return new EmployeeModel([
            'ndp' => $row['ndp'] ?? null,
            'name' => $row['name'] ?? null,
            'department' => $row['department'] ?? null, // Backward compatibility
            'department_id' => $department ? $department->id : null,
            'position' => $row['position'] ?? null, // Backward compatibility
            'position_id' => $position ? $position->id : null,
            'grade' => $row['grade'] ?? null,
            'family_composition' => $row['family_composition'] ?? null, // Backward compatibility
            'family_composition_id' => $familyComposition ? $familyComposition->id : null,
            'monthly_salary' => $row['monthly_salary'] ?? null,
            'status' => $row['status'] ?? 'active',
            'hire_date' => $row['hire_date'] ? $this->parseFlexibleDate($row['hire_date']) : null,
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
