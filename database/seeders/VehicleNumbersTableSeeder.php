<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VehicleNumber;

class VehicleNumbersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicleNumbers = [
            ['number' => 'BE 1234 AB', 'description' => 'Truk Angkut TBS', 'is_active' => true],
            ['number' => 'BE 5678 CD', 'description' => 'Truk Angkut TBS', 'is_active' => true],
            ['number' => 'BE 9012 EF', 'description' => 'Truk Angkut TBS', 'is_active' => true],
            ['number' => 'BE 3456 GH', 'description' => 'Truk Angkut TBS', 'is_active' => true],
            ['number' => 'BE 7890 IJ', 'description' => 'Truk Angkut TBS', 'is_active' => true],
        ];

        foreach ($vehicleNumbers as $vehicleNumber) {
            VehicleNumber::create($vehicleNumber);
        }
    }
}
