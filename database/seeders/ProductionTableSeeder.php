<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Production;
use App\Models\Pks;
use App\Models\Division;
use App\Models\VehicleNumber;
use Illuminate\Support\Facades\DB;

class ProductionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the PKS records
        $pksCentral = Pks::where('name', 'PKS Central')->first();
        $pksEast = Pks::where('name', 'PKS East')->first();
        $pksWest = Pks::where('name', 'PKS West')->first();
        
        // Get the division records (assuming they exist)
        $divisionA = Division::where('name', 'Afdeling A')->first() ?? Division::first();
        $divisionB = Division::where('name', 'Afdeling B')->first() ?? Division::skip(1)->first();
        $divisionC = Division::where('name', 'Afdeling C')->first() ?? Division::skip(2)->first();
        
        // Get vehicle records
        $vehicle1 = VehicleNumber::first();
        $vehicle2 = VehicleNumber::skip(1)->first();
        $vehicle3 = VehicleNumber::skip(2)->first();
        $vehicle4 = VehicleNumber::skip(3)->first();
        $vehicle5 = VehicleNumber::skip(4)->first();

        $productions = [
            [
                'transaction_number' => 'PROD-001',
                'date' => '2025-10-01',
                'sp_number' => 'SP-2025-001',
                'vehicle_number' => 'BK 1234 AB',
                'vehicle_id' => $vehicle1?->id,
                'tbs_quantity' => 1250.50,
                'kg_quantity' => 1100.75,
                'division' => 'Afdeling A',
                'division_id' => $divisionA?->id,
                'pks' => 'PKS Central',
                'pks_id' => $pksCentral?->id,
                'sp_photo_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transaction_number' => 'PROD-002',
                'date' => '2025-10-02',
                'sp_number' => 'SP-2025-002',
                'vehicle_number' => 'BK 5678 CD',
                'vehicle_id' => $vehicle2?->id,
                'tbs_quantity' => 1320.25,
                'kg_quantity' => 1165.50,
                'division' => 'Afdeling B',
                'division_id' => $divisionB?->id,
                'pks' => 'PKS Central',
                'pks_id' => $pksCentral?->id,
                'sp_photo_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transaction_number' => 'PROD-003',
                'date' => '2025-10-03',
                'sp_number' => 'SP-2025-003',
                'vehicle_number' => 'BK 9012 EF',
                'vehicle_id' => $vehicle3?->id,
                'tbs_quantity' => 1180.75,
                'kg_quantity' => 1040.25,
                'division' => 'Afdeling C',
                'division_id' => $divisionC?->id,
                'pks' => 'PKS East',
                'pks_id' => $pksEast?->id,
                'sp_photo_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transaction_number' => 'PROD-004',
                'date' => '2025-10-04',
                'sp_number' => 'SP-2025-004',
                'vehicle_number' => 'BK 3456 GH',
                'vehicle_id' => $vehicle4?->id,
                'tbs_quantity' => 1420.00,
                'kg_quantity' => 1250.00,
                'division' => 'Afdeling A',
                'division_id' => $divisionA?->id,
                'pks' => 'PKS Central',
                'pks_id' => $pksCentral?->id,
                'sp_photo_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transaction_number' => 'PROD-005',
                'date' => '2025-10-05',
                'sp_number' => 'SP-2025-005',
                'vehicle_number' => 'BK 7890 IJ',
                'vehicle_id' => $vehicle5?->id,
                'tbs_quantity' => 1350.25,
                'kg_quantity' => 1190.75,
                'division' => 'Afdeling B',
                'division_id' => $divisionB?->id,
                'pks' => 'PKS West',
                'pks_id' => $pksWest?->id,
                'sp_photo_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('production')->insert($productions);
    }
}
