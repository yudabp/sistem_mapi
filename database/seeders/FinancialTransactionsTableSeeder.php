<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinancialTransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = [
            // Income transactions
            [
                'transaction_date' => '2025-10-05',
                'transaction_number' => 'INC-001',
                'transaction_type' => 'income',
                'amount' => 3852625.00,
                'source_destination' => 'PT Sawit Makmur',
                'received_by' => 'Finance Dept',
                'proof_document_path' => null,
                'notes' => 'Payment for TBS delivery',
                'category' => 'Sales Revenue',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transaction_date' => '2025-10-06',
                'transaction_number' => 'INC-002',
                'transaction_type' => 'income',
                'amount' => 4195800.00,
                'source_destination' => 'CV Minyak Sejahtera',
                'received_by' => 'Finance Dept',
                'proof_document_path' => null,
                'notes' => 'Payment for TBS delivery',
                'category' => 'Sales Revenue',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transaction_date' => '2025-10-07',
                'transaction_number' => 'INC-003',
                'transaction_type' => 'income',
                'amount' => 3536850.00,
                'source_destination' => 'PT Palm Oil Indonesia',
                'received_by' => 'Finance Dept',
                'proof_document_path' => null,
                'notes' => 'Payment for TBS delivery',
                'category' => 'Sales Revenue',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Expense transactions
            [
                'transaction_date' => '2025-10-01',
                'transaction_number' => 'EXP-001',
                'transaction_type' => 'expense',
                'amount' => 2500000.00,
                'source_destination' => 'Supplier Pupuk',
                'received_by' => 'Procurement Dept',
                'proof_document_path' => null,
                'notes' => 'Purchase of fertilizers',
                'category' => 'Operational Cost',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transaction_date' => '2025-10-02',
                'transaction_number' => 'EXP-002',
                'transaction_type' => 'expense',
                'amount' => 1800000.00,
                'source_destination' => 'Fuel Supplier',
                'received_by' => 'Operations Dept',
                'proof_document_path' => null,
                'notes' => 'Fuel for vehicles and machinery',
                'category' => 'Operational Cost',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transaction_date' => '2025-10-03',
                'transaction_number' => 'EXP-003',
                'transaction_type' => 'expense',
                'amount' => 7500000.00,
                'source_destination' => 'Employee Salaries',
                'received_by' => 'HR Dept',
                'proof_document_path' => null,
                'notes' => 'Monthly employee salaries',
                'category' => 'Personnel Cost',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transaction_date' => '2025-10-04',
                'transaction_number' => 'EXP-004',
                'transaction_type' => 'expense',
                'amount' => 3200000.00,
                'source_destination' => 'Maintenance Services',
                'received_by' => 'Operations Dept',
                'proof_document_path' => null,
                'notes' => 'Equipment maintenance',
                'category' => 'Maintenance Cost',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transaction_date' => '2025-10-08',
                'transaction_number' => 'EXP-005',
                'transaction_type' => 'expense',
                'amount' => 1500000.00,
                'source_destination' => 'Transport Services',
                'received_by' => 'Logistics Dept',
                'proof_document_path' => null,
                'notes' => 'Transportation costs',
                'category' => 'Logistics Cost',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('financial_transactions')->insert($transactions);
    }
}
