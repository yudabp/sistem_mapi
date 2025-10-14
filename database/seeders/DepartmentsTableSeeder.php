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
            ['name' => 'Bagian Produksi', 'description' => 'Bagian yang menangani proses produksi', 'is_active' => true],
            ['name' => 'Bagian Administrasi', 'description' => 'Bagian yang menangani administrasi kantor', 'is_active' => true],
            ['name' => 'Bagian Keuangan', 'description' => 'Bagian yang menangani keuangan perusahaan', 'is_active' => true],
            ['name' => 'Bagian SDM', 'description' => 'Bagian yang menangani Sumber Daya Manusia', 'is_active' => true],
            ['name' => 'Bagian Umum', 'description' => 'Bagian yang menangani urusan umum', 'is_active' => true],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
