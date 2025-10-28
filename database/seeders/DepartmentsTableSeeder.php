<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Production', 'description' => 'Department handling production processes', 'is_active' => true],
            ['name' => 'Sales', 'description' => 'Department handling sales and marketing', 'is_active' => true],
            ['name' => 'Finance', 'description' => 'Department handling financial matters', 'is_active' => true],
            ['name' => 'HR', 'description' => 'Human Resources Department', 'is_active' => true],
            ['name' => 'IT', 'description' => 'Information Technology Department', 'is_active' => true],
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(['name' => $department['name']], $department);
        }
    }
}
