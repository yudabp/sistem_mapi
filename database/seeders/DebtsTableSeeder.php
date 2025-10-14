<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DebtsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $debts = [
            [
                'amount' => 5000000.00,
                'creditor' => 'PT Pupuk Indonesia',
                'due_date' => '2025-10-30',
                'description' => 'Fertilizer purchase payment',
                'proof_document_path' => null,
                'status' => 'unpaid',
                'paid_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'amount' => 3200000.00,
                'creditor' => 'CV Alat Berat',
                'due_date' => '2025-10-25',
                'description' => 'Heavy equipment rental',
                'proof_document_path' => null,
                'status' => 'unpaid',
                'paid_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'amount' => 7500000.00,
                'creditor' => 'Bank Mandiri',
                'due_date' => '2025-11-05',
                'description' => 'Monthly loan installment',
                'proof_document_path' => null,
                'status' => 'unpaid',
                'paid_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'amount' => 2500000.00,
                'creditor' => 'PT Transport Nusantara',
                'due_date' => '2025-09-30',
                'description' => 'Transportation services',
                'proof_document_path' => null,
                'status' => 'paid',
                'paid_date' => '2025-09-28',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'amount' => 4100000.00,
                'creditor' => 'CV Maintenance Services',
                'due_date' => '2025-10-20',
                'description' => 'Equipment maintenance services',
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
