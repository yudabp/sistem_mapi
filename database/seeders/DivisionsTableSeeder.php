<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            ['name' => 'Afdeling I', 'description' => 'Afdeling Pemeliharaan Tanaman', 'is_active' => true],
            ['name' => 'Afdeling II', 'description' => 'Afdeling Pemeliharaan Tanaman', 'is_active' => true],
            ['name' => 'Afdeling III', 'description' => 'Afdeling Pemeliharaan Tanaman', 'is_active' => true],
            ['name' => 'Afdeling IV', 'description' => 'Afdeling Pemeliharaan Tanaman', 'is_active' => true],
            ['name' => 'Afdeling V', 'description' => 'Afdeling Pemeliharaan Tanaman', 'is_active' => true],
        ];

        foreach ($divisions as $division) {
            Division::create($division);
        }
    }
}
