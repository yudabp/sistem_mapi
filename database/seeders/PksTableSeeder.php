<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pks;

class PksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pks = [
            ['name' => 'PKS Central', 'description' => 'Pabrik Kelapa Sawit Pusat', 'is_active' => true],
            ['name' => 'PKS East', 'description' => 'Pabrik Kelapa Sawit Timur', 'is_active' => true],
            ['name' => 'PKS West', 'description' => 'Pabrik Kelapa Sawit Barat', 'is_active' => true],
            ['name' => 'PKS North', 'description' => 'Pabrik Kelapa Sawit Utara', 'is_active' => true],
            ['name' => 'PKS South', 'description' => 'Pabrik Kelapa Sawit Selatan', 'is_active' => true],
        ];

        foreach ($pks as $pksItem) {
            Pks::create($pksItem);
        }
    }
}
