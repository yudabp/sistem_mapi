<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\FamilyComposition;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get department records
        $productionDept = Department::where('name', 'like', '%Production%')->first() ?? Department::first();
        $salesDept = Department::where('name', 'like', '%Sales%')->first() ?? Department::skip(1)->first();
        $financeDept = Department::where('name', 'like', '%Finance%')->first() ?? Department::skip(2)->first();
        $adminDept = Department::where('name', 'like', '%Admin%')->first() ?? Department::skip(3)->first();
        
        // Get position records
        $foremanPos = Position::where('name', 'like', '%Foreman%')->first() ?? Position::first();
        $salesManagerPos = Position::where('name', 'like', '%Sales Manager%')->first() ?? Position::skip(1)->first();
        $financeOfficerPos = Position::where('name', 'like', '%Finance Officer%')->first() ?? Position::skip(2)->first();
        $operatorPos = Position::where('name', 'like', '%Operator%')->first() ?? Position::skip(3)->first();
        $adminAssistantPos = Position::where('name', 'like', '%Admin Assistant%')->first() ?? Position::skip(4)->first();
        
        // Get family composition records
        $family4 = FamilyComposition::where('number', 4)->first() ?? FamilyComposition::first();
        $family3 = FamilyComposition::where('number', 3)->first() ?? FamilyComposition::skip(1)->first();
        $family5 = FamilyComposition::where('number', 5)->first() ?? FamilyComposition::skip(2)->first();
        $family2 = FamilyComposition::where('number', 2)->first() ?? FamilyComposition::skip(3)->first();

        $employees = [
            [
                'ndp' => 'EMP001',
                'name' => 'Budi Santoso',
                'department' => 'Production',
                'department_id' => $productionDept?->id,
                'position' => 'Foreman',
                'position_id' => $foremanPos?->id,
                'grade' => 'B2',
                'family_composition' => 4,
                'family_composition_id' => $family4?->id,
                'monthly_salary' => 7500000,
                'status' => 'active',
                'hire_date' => '2020-01-15',
                'address' => 'Jl. Merdeka No. 123, Jakarta',
                'phone' => '081234567890',
                'email' => 'budi.santoso@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ndp' => 'EMP002',
                'name' => 'Siti Rahayu',
                'department' => 'Sales',
                'department_id' => $salesDept?->id,
                'position' => 'Sales Manager',
                'position_id' => $salesManagerPos?->id,
                'grade' => 'A1',
                'family_composition' => 3,
                'family_composition_id' => $family3?->id,
                'monthly_salary' => 12000000,
                'status' => 'active',
                'hire_date' => '2019-03-20',
                'address' => 'Jl. Sudirman No. 456, Jakarta',
                'phone' => '081298765432',
                'email' => 'siti.rahayu@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ndp' => 'EMP003',
                'name' => 'Ahmad Prabowo',
                'department' => 'Finance',
                'department_id' => $financeDept?->id,
                'position' => 'Finance Officer',
                'position_id' => $financeOfficerPos?->id,
                'grade' => 'C1',
                'family_composition' => 5,
                'family_composition_id' => $family5?->id,
                'monthly_salary' => 8500000,
                'status' => 'active',
                'hire_date' => '2021-07-10',
                'address' => 'Jl. Gatot Subroto No. 789, Jakarta',
                'phone' => '085678901234',
                'email' => 'ahmad.prabowo@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ndp' => 'EMP004',
                'name' => 'Dewi Kurniawati',
                'department' => 'Production',
                'department_id' => $productionDept?->id,
                'position' => 'Operator',
                'position_id' => $operatorPos?->id,
                'grade' => 'C2',
                'family_composition' => 2,
                'family_composition_id' => $family2?->id,
                'monthly_salary' => 5500000,
                'status' => 'active',
                'hire_date' => '2022-02-05',
                'address' => 'Jl. Thamrin No. 321, Jakarta',
                'phone' => '089876543210',
                'email' => 'dewi.kurniawati@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ndp' => 'EMP005',
                'name' => 'Joko Widodo',
                'department' => 'Administration',
                'department_id' => $adminDept?->id,
                'position' => 'Admin Assistant',
                'position_id' => $adminAssistantPos?->id,
                'grade' => 'B1',
                'family_composition' => 4,
                'family_composition_id' => $family4?->id,
                'monthly_salary' => 6500000,
                'status' => 'inactive',
                'hire_date' => '2018-11-12',
                'address' => 'Jl. Kebon Jeruk No. 654, Jakarta',
                'phone' => '081122334455',
                'email' => 'joko.widodo@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('employees')->insert($employees);
    }
}
