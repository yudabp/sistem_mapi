<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmploymentStatus;

class EmploymentStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employmentStatuses = [
            ['name' => 'Aktif', 'value' => 'active', 'description' => 'Status karyawan aktif bekerja', 'is_active' => true],
            ['name' => 'Tidak Aktif', 'value' => 'inactive', 'description' => 'Status karyawan tidak aktif bekerja', 'is_active' => true],
            ['name' => 'Pensiun', 'value' => 'retired', 'description' => 'Status karyawan yang sudah pensiun', 'is_active' => true],
            ['name' => 'Mengundurkan Diri', 'value' => 'resigned', 'description' => 'Status karyawan yang mengundurkan diri', 'is_active' => true],
            ['name' => 'PHK', 'value' => 'terminated', 'description' => 'Status karyawan yang di-PHK', 'is_active' => true],
        ];

        foreach ($employmentStatuses as $employmentStatus) {
            EmploymentStatus::create($employmentStatus);
        }
    }
}
