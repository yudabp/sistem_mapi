<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Production;
use App\Models\Pks;
use App\Models\Division;
use App\Models\VehicleNumber;
use Illuminate\Support\Facades\DB;

class ProductionTableSeeder50 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data to avoid duplicates
        DB::table('production')->delete();

        // Get the PKS records
        $pksCentral = Pks::where('name', 'PKS Central')->first();
        $pksEast = Pks::where('name', 'PKS East')->first();
        $pksWest = Pks::where('name', 'PKS West')->first();
        $pksNorth = Pks::where('name', 'PKS North')->first() ?? Pks::first();
        $pksSouth = Pks::where('name', 'PKS South')->first() ?? Pks::skip(1)->first();

        // Get the division records
        $divisionA = Division::where('name', 'Afdeling A')->first() ?? Division::first();
        $divisionB = Division::where('name', 'Afdeling B')->first() ?? Division::skip(1)->first();
        $divisionC = Division::where('name', 'Afdeling C')->first() ?? Division::skip(2)->first();
        $divisionD = Division::where('name', 'Afdeling D')->first() ?? Division::skip(3)->first();
        $divisionE = Division::where('name', 'Afdeling E')->first() ?? Division::skip(4)->first();
        $divisionF = Division::where('name', 'Afdeling F')->first() ?? Division::skip(5)->first();

        // Get vehicle records
        $vehicles = VehicleNumber::limit(15)->get();
        if ($vehicles->count() == 0) {
            // Create some vehicles if none exist
            for ($i = 1; $i <= 15; $i++) {
                $vehicle = VehicleNumber::create([
                    'number' => 'BK ' . (1000 + $i) . ' ' . chr(64 + $i),
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $vehicles->push($vehicle);
            }
            $vehicles = VehicleNumber::limit(15)->get();
        }

        $productions = [];
        for ($i = 1; $i <= 50; $i++) {
            $vehicleIndex = ($i - 1) % $vehicles->count();
            $divisionIndex = ($i - 1) % 6; // 6 divisions
            $pksIndex = ($i - 1) % 5; // 5 PKS

            // Determine division
            switch ($divisionIndex) {
                case 0: $division = $divisionA; $divisionName = 'Afdeling A'; break;
                case 1: $division = $divisionB; $divisionName = 'Afdeling B'; break;
                case 2: $division = $divisionC; $divisionName = 'Afdeling C'; break;
                case 3: $division = $divisionD; $divisionName = 'Afdeling D'; break;
                case 4: $division = $divisionE; $divisionName = 'Afdeling E'; break;
                case 5: $division = $divisionF; $divisionName = 'Afdeling F'; break;
                default: $division = $divisionA; $divisionName = 'Afdeling A'; break;
            }

            // Determine PKS
            switch ($pksIndex) {
                case 0: $pks = $pksCentral; $pksName = 'PKS Central'; break;
                case 1: $pks = $pksEast; $pksName = 'PKS East'; break;
                case 2: $pks = $pksWest; $pksName = 'PKS West'; break;
                case 3: $pks = $pksNorth; $pksName = 'PKS North'; break;
                case 4: $pks = $pksSouth; $pksName = 'PKS South'; break;
                default: $pks = $pksCentral; $pksName = 'PKS Central'; break;
            }

            $date = now()->subDays(50 - $i)->format('Y-m-d');
            $tbsQuantity = 1000 + (mt_rand(100, 500) / 10);
            $kgQuantity = $tbsQuantity * 0.87; // Approximately 87% conversion rate

            $productions[] = [
                'transaction_number' => 'PROD-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'date' => $date,
                'sp_number' => 'SP-2025-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'vehicle_number' => $vehicles[$vehicleIndex]->number ?? 'BK 1234 AB',
                'vehicle_id' => $vehicles[$vehicleIndex]->id ?? null,
                'tbs_quantity' => $tbsQuantity,
                'kg_quantity' => $kgQuantity,
                'division' => $divisionName,
                'division_id' => $division?->id,
                'pks' => $pksName,
                'pks_id' => $pks?->id,
                'sp_photo_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('production')->insert($productions);
    }
}