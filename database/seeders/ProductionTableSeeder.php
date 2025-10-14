<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productions = [
            [
                'transaction_number' => 'PROD-001',
                'date' => '2025-10-01',
                'sp_number' => 'SP-2025-001',
                'vehicle_number' => 'BK 1234 AB',
                'tbs_quantity' => 1250.50,
                'kg_quantity' => 1100.75,
                'division' => 'Afdeling A',
                'pks' => 'PKS Central',
                'sp_photo_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transaction_number' => 'PROD-002',
                'date' => '2025-10-02',
                'sp_number' => 'SP-2025-002',
                'vehicle_number' => 'BK 5678 CD',
                'tbs_quantity' => 1320.25,
                'kg_quantity' => 1165.50,
                'division' => 'Afdeling B',
                'pks' => 'PKS Central',
                'sp_photo_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transaction_number' => 'PROD-003',
                'date' => '2025-10-03',
                'sp_number' => 'SP-2025-003',
                'vehicle_number' => 'BK 9012 EF',
                'tbs_quantity' => 1180.75,
                'kg_quantity' => 1040.25,
                'division' => 'Afdeling C',
                'pks' => 'PKS East',
                'sp_photo_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transaction_number' => 'PROD-004',
                'date' => '2025-10-04',
                'sp_number' => 'SP-2025-004',
                'vehicle_number' => 'BK 3456 GH',
                'tbs_quantity' => 1420.00,
                'kg_quantity' => 1250.00,
                'division' => 'Afdeling A',
                'pks' => 'PKS Central',
                'sp_photo_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transaction_number' => 'PROD-005',
                'date' => '2025-10-05',
                'sp_number' => 'SP-2025-005',
                'vehicle_number' => 'BK 7890 IJ',
                'tbs_quantity' => 1350.25,
                'kg_quantity' => 1190.75,
                'division' => 'Afdeling B',
                'pks' => 'PKS West',
                'sp_photo_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('production')->insert($productions);
    }
}
