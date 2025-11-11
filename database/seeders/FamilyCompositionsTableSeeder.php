<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FamilyComposition;

class FamilyCompositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $familyCompositions = [
            ['name' => 'TK/1', 'description' => 'Satu Kepala Keluarga', 'is_active' => true],
            ['name' => 'TK/2', 'description' => 'Dua Orang Anak', 'is_active' => true],
            ['name' => 'TK/3', 'description' => 'Tiga Orang Anak', 'is_active' => true],
            ['name' => 'TK/4', 'description' => 'Empat Orang Anak', 'is_active' => true],
            ['name' => 'TK/5', 'description' => 'Lima Orang Anak', 'is_active' => true],
        ];

        foreach ($familyCompositions as $familyComposition) {
            FamilyComposition::create($familyComposition);
        }
    }
}
