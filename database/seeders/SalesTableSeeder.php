<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\Production;
use Illuminate\Support\Facades\DB;

class SalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get production records by transaction number
        $production1 = Production::where('transaction_number', 'PROD-001')->first();
        $production2 = Production::where('transaction_number', 'PROD-002')->first();
        $production3 = Production::where('transaction_number', 'PROD-003')->first();
        $production4 = Production::where('transaction_number', 'PROD-004')->first();
        $production5 = Production::where('transaction_number', 'PROD-005')->first();

        $sales = [
            [
                'sp_number' => 'SP-2025-001',
                'production_id' => $production1?->id,
                'tbs_quantity' => 1250.50,
                'kg_quantity' => 1100.75,
                'price_per_kg' => 3500.00,
                'total_amount' => 3852625.00,
                'sale_date' => '2025-10-05',
                'customer_name' => 'PT Sawit Makmur',
                'customer_address' => 'Jl. Industri No. 1, Medan',
                'sales_proof_path' => null,
                'is_taxable' => false,
                'tax_percentage' => 0,
                'tax_amount' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sp_number' => 'SP-2025-002',
                'production_id' => $production2?->id,
                'tbs_quantity' => 1320.25,
                'kg_quantity' => 1165.50,
                'price_per_kg' => 3600.00,
                'total_amount' => 4195800.00,
                'sale_date' => '2025-10-06',
                'customer_name' => 'CV Minyak Sejahtera',
                'customer_address' => 'Jl. Raya No. 25, Pekanbaru',
                'sales_proof_path' => null,
                'is_taxable' => true,
                'tax_percentage' => 10,
                'tax_amount' => 419580.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sp_number' => 'SP-2025-003',
                'production_id' => $production3?->id,
                'tbs_quantity' => 1180.75,
                'kg_quantity' => 1040.25,
                'price_per_kg' => 3400.00,
                'total_amount' => 3536850.00,
                'sale_date' => '2025-10-07',
                'customer_name' => 'PT Palm Oil Indonesia',
                'customer_address' => 'Jl. Merdeka No. 100, Jakarta',
                'sales_proof_path' => null,
                'is_taxable' => false,
                'tax_percentage' => 0,
                'tax_amount' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sp_number' => 'SP-2025-004',
                'production_id' => $production4?->id,
                'tbs_quantity' => 1420.00,
                'kg_quantity' => 1250.00,
                'price_per_kg' => 3700.00,
                'total_amount' => 4625000.00,
                'sale_date' => '2025-10-08',
                'customer_name' => 'PT Global Palm',
                'customer_address' => 'Jl. Asia Afrika No. 50, Bandung',
                'sales_proof_path' => null,
                'is_taxable' => true,
                'tax_percentage' => 11,
                'tax_amount' => 508750.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sp_number' => 'SP-2025-005',
                'production_id' => $production5?->id,
                'tbs_quantity' => 1350.25,
                'kg_quantity' => 1190.75,
                'price_per_kg' => 3550.00,
                'total_amount' => 4226162.50,
                'sale_date' => '2025-10-09',
                'customer_name' => 'CV Kelapa Sawit Jaya',
                'customer_address' => 'Jl. Diponegoro No. 75, Surabaya',
                'sales_proof_path' => null,
                'is_taxable' => false,
                'tax_percentage' => 0,
                'tax_amount' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('sales')->insert($sales);
    }
}
