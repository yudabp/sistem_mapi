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
            ['name' => 'Afdeling A', 'description' => 'Afdeling Pemeliharaan Tanaman A', 'is_active' => true],
            ['name' => 'Afdeling B', 'description' => 'Afdeling Pemeliharaan Tanaman B', 'is_active' => true],
            ['name' => 'Afdeling C', 'description' => 'Afdeling Pemeliharaan Tanaman C', 'is_active' => true],
            ['name' => 'Afdeling D', 'description' => 'Afdeling Pemeliharaan Tanaman D', 'is_active' => true],
            ['name' => 'Afdeling E', 'description' => 'Afdeling Pemeliharaan Tanaman E', 'is_active' => true],
        ];

        foreach ($divisions as $division) {
            Division::create($division);
        }
    }
}
