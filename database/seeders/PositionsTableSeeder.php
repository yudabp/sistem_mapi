<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            ['name' => 'Foreman', 'description' => 'Foreman position overseeing production workers', 'is_active' => true],
            ['name' => 'Sales Manager', 'description' => 'Manager position for sales department', 'is_active' => true],
            ['name' => 'Finance Officer', 'description' => 'Officer position for finance department', 'is_active' => true],
            ['name' => 'Operator', 'description' => 'Operator position for production line', 'is_active' => true],
            ['name' => 'HR Manager', 'description' => 'Manager position for HR department', 'is_active' => true],
            ['name' => 'IT Support', 'description' => 'Support position for IT department', 'is_active' => true],
            ['name' => 'Director', 'description' => 'Director level position', 'is_active' => true],
            ['name' => 'Supervisor', 'description' => 'Supervisor position overseeing team members', 'is_active' => true],
        ];

        foreach ($positions as $position) {
            Position::firstOrCreate(['name' => $position['name']], $position);
        }
    }
}
