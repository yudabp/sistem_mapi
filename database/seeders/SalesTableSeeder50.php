<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\Production;
use Illuminate\Support\Facades\DB;

class SalesTableSeeder50 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data to avoid duplicates
        DB::table('sales')->delete();

        // Get first 50 production records to link with sales
        $productionRecords = Production::limit(50)->get();
        
        if ($productionRecords->count() < 50) {
            // If production records are not enough, we need to create more
            // This would mean we need to run ProductionTableSeeder50 first
            $productionRecords = Production::all();
        }

        $sales = [];
        for ($i = 1; $i <= 50; $i++) {
            $productionIndex = ($i - 1) % $productionRecords->count();
            $production = $productionRecords[$productionIndex];
            
            $pricePerKg = 3400 + (mt_rand(0, 400)); // Price between 3400 and 3800
            $totalAmount = ($production->kg_quantity ?? 1000.00) * $pricePerKg;
            $isTaxable = (mt_rand(0, 1) === 1); // 50% chance to be taxable
            $taxPercentage = $isTaxable ? (mt_rand(8, 11) / 100) : 0;
            $taxAmount = $isTaxable ? ($totalAmount * $taxPercentage) : 0;

            $sales[] = [
                'sp_number' => 'SP-2025-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'production_id' => $production->id ?? null,
                'tbs_quantity' => $production->tbs_quantity ?? 1200.00,
                'kg_quantity' => $production->kg_quantity ?? 1000.00,
                'price_per_kg' => $pricePerKg,
                'total_amount' => $totalAmount,
                'sale_date' => now()->subDays(49 - $i)->format('Y-m-d'),
                'sales_proof_path' => null,
                'is_taxable' => $isTaxable,
                'tax_percentage' => $isTaxable ? ($taxPercentage * 100) : 0,
                'tax_amount' => $taxAmount,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('sales')->insert($sales);
    }
}