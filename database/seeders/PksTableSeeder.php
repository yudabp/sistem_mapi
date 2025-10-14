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
            ['name' => 'PKS I', 'description' => 'Pabrik Kelapa Sawit I', 'is_active' => true],
            ['name' => 'PKS II', 'description' => 'Pabrik Kelapa Sawit II', 'is_active' => true],
            ['name' => 'PKS III', 'description' => 'Pabrik Kelapa Sawit III', 'is_active' => true],
            ['name' => 'PKS IV', 'description' => 'Pabrik Kelapa Sawit IV', 'is_active' => true],
        ];

        foreach ($pks as $pksItem) {
            Pks::create($pksItem);
        }
    }
}
