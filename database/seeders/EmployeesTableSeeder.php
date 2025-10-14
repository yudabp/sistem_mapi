<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'ndp' => 'EMP001',
                'name' => 'Budi Santoso',
                'department' => 'Production',
                'position' => 'Foreman',
                'grade' => 'B2',
                'family_composition' => 4,
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
                'position' => 'Sales Manager',
                'grade' => 'A1',
                'family_composition' => 3,
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
                'position' => 'Finance Officer',
                'grade' => 'C1',
                'family_composition' => 5,
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
                'position' => 'Operator',
                'grade' => 'C2',
                'family_composition' => 2,
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
                'position' => 'Admin Assistant',
                'grade' => 'B1',
                'family_composition' => 4,
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
