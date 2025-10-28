<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\FamilyComposition;
use App\Models\EmploymentStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data to avoid duplicates
        DB::table('employees')->delete();

        // Get department records
        $productionDept = Department::where('name', 'Production')->first();
        $salesDept = Department::where('name', 'Sales')->first();
        $financeDept = Department::where('name', 'Finance')->first();
        $hrDept = Department::where('name', 'HR')->first();
        $itDept = Department::where('name', 'IT')->first();

        // Get position records
        $foremanPos = Position::where('name', 'Foreman')->first();
        $salesManagerPos = Position::where('name', 'Sales Manager')->first();
        $financeOfficerPos = Position::where('name', 'Finance Officer')->first();
        $operatorPos = Position::where('name', 'Operator')->first();
        $hrManagerPos = Position::where('name', 'HR Manager')->first();
        $itSupportPos = Position::where('name', 'IT Support')->first();
        $directorPos = Position::where('name', 'Director')->first();
        $supervisorPos = Position::where('name', 'Supervisor')->first();

        // Get family composition records
        $family1 = FamilyComposition::where('number', 1)->first();
        $family2 = FamilyComposition::where('number', 2)->first();
        $family3 = FamilyComposition::where('number', 3)->first();
        $family4 = FamilyComposition::where('number', 4)->first();
        $family5 = FamilyComposition::where('number', 5)->first();
        $family6 = FamilyComposition::where('number', 6)->first();

        // Get employment status records
        $statusActive = EmploymentStatus::where('value', 'active')->first();
        $statusInactive = EmploymentStatus::where('value', 'inactive')->first();

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
                'employment_status_id' => $statusActive?->id,
                'employment_status_id' => $statusActive?->id,
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
                'employment_status_id' => $statusActive?->id,
                'employment_status_id' => $statusActive?->id,
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
                'employment_status_id' => $statusActive?->id,
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
                'employment_status_id' => $statusActive?->id,
                'hire_date' => '2022-02-05',
                'address' => 'Jl. Thamrin No. 321, Jakarta',
                'phone' => '089876543210',
                'email' => 'dewi.kurniawati@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ndp' => 'EMP005',
                'name' => 'Rina Wijaya',
                'department' => 'HR',
                'department_id' => $hrDept?->id,
                'position' => 'HR Manager',
                'position_id' => $hrManagerPos?->id,
                'grade' => 'A2',
                'family_composition' => 3,
                'family_composition_id' => $family3?->id,
                'monthly_salary' => 10000000,
                'status' => 'active',
                'employment_status_id' => $statusActive?->id,
                'hire_date' => '2019-06-15',
                'address' => 'Jl. Rasuna Said No. 111, Jakarta',
                'phone' => '082134567890',
                'email' => 'rina.wijaya@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ndp' => 'EMP006',
                'name' => 'Eko Prasetyo',
                'department' => 'IT',
                'department_id' => $itDept?->id,
                'position' => 'IT Support',
                'position_id' => $itSupportPos?->id,
                'grade' => 'B1',
                'family_composition' => 2,
                'family_composition_id' => $family2?->id,
                'monthly_salary' => 7000000,
                'status' => 'active',
                'employment_status_id' => $statusActive?->id,
                'hire_date' => '2020-09-01',
                'address' => 'Jl. Casablanca No. 88, Jakarta',
                'phone' => '082198765432',
                'email' => 'eko.prasetyo@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ndp' => 'EMP007',
                'name' => 'Hartono Sukarno',
                'department' => 'Sales',
                'department_id' => $salesDept?->id,
                'position' => 'Sales Manager',
                'position_id' => $salesManagerPos?->id,
                'grade' => 'A1',
                'family_composition' => 4,
                'family_composition_id' => $family4?->id,
                'monthly_salary' => 11500000,
                'status' => 'active',
                'employment_status_id' => $statusActive?->id,
                'hire_date' => '2018-04-10',
                'address' => 'Jl. Sudirman No. 200, Jakarta',
                'phone' => '083456789012',
                'email' => 'hartono.sukarno@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ndp' => 'EMP008',
                'name' => 'Lisa Permata',
                'department' => 'Finance',
                'department_id' => $financeDept?->id,
                'position' => 'Finance Officer',
                'position_id' => $financeOfficerPos?->id,
                'grade' => 'C1',
                'family_composition' => 1,
                'family_composition_id' => $family1?->id,
                'monthly_salary' => 8000000,
                'status' => 'active',
                'employment_status_id' => $statusActive?->id,
                'hire_date' => '2021-01-20',
                'address' => 'Jl. Gatot Subroto No. 500, Jakarta',
                'phone' => '084567890123',
                'email' => 'lisa.permata@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ndp' => 'EMP009',
                'name' => 'Andi Kusuma',
                'department' => 'Production',
                'department_id' => $productionDept?->id,
                'position' => 'Supervisor',
                'position_id' => $supervisorPos?->id,
                'grade' => 'B3',
                'family_composition' => 6,
                'family_composition_id' => $family6?->id,
                'monthly_salary' => 9000000,
                'status' => 'active',
                'employment_status_id' => $statusActive?->id,
                'hire_date' => '2017-08-15',
                'address' => 'Jl. Pangeran Jayakarta No. 33, Jakarta',
                'phone' => '085678901234',
                'email' => 'andi.kusuma@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ndp' => 'EMP010',
                'name' => 'Maya Sari',
                'department' => 'Production',
                'department_id' => $productionDept?->id,
                'position' => 'Operator',
                'position_id' => $operatorPos?->id,
                'grade' => 'C2',
                'family_composition' => 1,
                'family_composition_id' => $family1?->id,
                'monthly_salary' => 5200000,
                'status' => 'active',
                'employment_status_id' => $statusActive?->id,
                'hire_date' => '2022-05-10',
                'address' => 'Jl. Mangga Dua No. 77, Jakarta',
                'phone' => '086789012345',
                'email' => 'maya.sari@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ndp' => 'EMP011',
                'name' => 'Bambang Sutrisno',
                'department' => 'Production',
                'department_id' => $productionDept?->id,
                'position' => 'Operator',
                'position_id' => $operatorPos?->id,
                'grade' => 'C2',
                'family_composition' => 3,
                'family_composition_id' => $family3?->id,
                'monthly_salary' => 5400000,
                'status' => 'inactive',
                'employment_status_id' => $statusInactive?->id,
                'hire_date' => '2019-11-20',
                'address' => 'Jl. Hayam Wuruk No. 155, Jakarta',
                'phone' => '087890123456',
                'email' => 'bambang.sutrisno@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ndp' => 'EMP012',
                'name' => 'Diana Putri',
                'department' => 'IT',
                'department_id' => $itDept?->id,
                'position' => 'Director',
                'position_id' => $directorPos?->id,
                'grade' => 'A1',
                'family_composition' => 4,
                'family_composition_id' => $family4?->id,
                'monthly_salary' => 25000000,
                'status' => 'active',
                'employment_status_id' => $statusActive?->id,
                'hire_date' => '2015-03-01',
                'address' => 'Jl. Senayan No. 99, Jakarta',
                'phone' => '088901234567',
                'email' => 'diana.putri@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('employees')->insert($employees);
    }
}
