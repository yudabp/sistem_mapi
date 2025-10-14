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
            ['name' => 'Manager', 'description' => 'Jabatan Manager', 'is_active' => true],
            ['name' => 'Supervisor', 'description' => 'Jabatan Supervisor', 'is_active' => true],
            ['name' => 'Kepala Bagian', 'description' => 'Jabatan Kepala Bagian', 'is_active' => true],
            ['name' => 'Staff', 'description' => 'Jabatan Staff', 'is_active' => true],
            ['name' => 'Karyawan', 'description' => 'Jabatan Karyawan', 'is_active' => true],
            ['name' => 'Mandor', 'description' => 'Jabatan Mandor', 'is_active' => true],
        ];

        foreach ($positions as $position) {
            Position::create($position);
        }
    }
}
