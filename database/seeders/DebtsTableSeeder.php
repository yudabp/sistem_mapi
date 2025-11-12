<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Debt;
use App\Models\MasterDebtType;
use Illuminate\Support\Facades\DB;

class DebtsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get debt type records
        $supplierDebt = MasterDebtType::where('name', 'Hutang Supplier')->first();
        $bankDebt = MasterDebtType::where('name', 'Hutang Bank')->first();
        $thirdPartyDebt = MasterDebtType::where('name', 'Hutang Pihak Ketiga')->first();
        $operationalDebt = MasterDebtType::where('name', 'Hutang Operasional')->first();

        $debts = [
            [
                'amount' => 5000000.00,
                'sisa_hutang' => 5000000.00,
                'cicilan_per_bulan' => 1000000.00,
                'creditor' => 'PT Pupuk Indonesia',
                'debt_type_id' => $supplierDebt?->id,
                'due_date' => '2025-10-30',
                'description' => 'Pembelian pupuk',
                'proof_document_path' => null,
                'status' => 'unpaid',
                'paid_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'amount' => 3200000.00,
                'sisa_hutang' => 3200000.00,
                'cicilan_per_bulan' => 800000.00,
                'creditor' => 'CV Alat Berat',
                'debt_type_id' => $thirdPartyDebt?->id,
                'due_date' => '2025-10-25',
                'description' => 'Sewa alat berat',
                'proof_document_path' => null,
                'status' => 'unpaid',
                'paid_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'amount' => 7500000.00,
                'sisa_hutang' => 7500000.00,
                'cicilan_per_bulan' => 1500000.00,
                'creditor' => 'Bank Mandiri',
                'debt_type_id' => $bankDebt?->id,
                'due_date' => '2025-11-05',
                'description' => 'Angsuran pinjaman bulanan',
                'proof_document_path' => null,
                'status' => 'unpaid',
                'paid_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'amount' => 2500000.00,
                'sisa_hutang' => 0.00,
                'cicilan_per_bulan' => 500000.00,
                'creditor' => 'PT Transport Nusantara',
                'debt_type_id' => $operationalDebt?->id,
                'due_date' => '2025-09-30',
                'description' => 'Jasa transportasi',
                'proof_document_path' => null,
                'status' => 'paid',
                'paid_date' => '2025-09-28',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'amount' => 4100000.00,
                'sisa_hutang' => 4100000.00,
                'cicilan_per_bulan' => 820000.00,
                'creditor' => 'CV Maintenance Services',
                'debt_type_id' => $operationalDebt?->id,
                'due_date' => '2025-10-20',
                'description' => 'Jasa maintenance peralatan',
                'proof_document_path' => null,
                'status' => 'unpaid',
                'paid_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('debts')->insert($debts);
    }
}
