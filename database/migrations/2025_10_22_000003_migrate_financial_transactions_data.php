<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate data from financial_transactions to keuangan_perusahaan and buku_kas_kebun
        // Based on the business logic, we'll categorize transactions:
        // - KP (Keuangan Perusahaan): Company-level financial transactions
        // - BKK (Buku Kas Kebun): Garden/plantation-level financial transactions
        
        $transactions = DB::table('financial_transactions')->get();
        
        foreach ($transactions as $transaction) {
            // Determine if this should go to KP or BKK based on category
            $isKP = $this->isKPTransaction($transaction);
            
            if ($isKP) {
                // Insert into keuangan_perusahaan (KP)
                DB::table('keuangan_perusahaan')->insert([
                    'transaction_date' => $transaction->transaction_date,
                    'transaction_number' => 'KP-' . $transaction->transaction_number,
                    'transaction_type' => $transaction->transaction_type,
                    'amount' => $transaction->amount,
                    'source_destination' => $transaction->source_destination,
                    'received_by' => $transaction->received_by,
                    'proof_document_path' => $transaction->proof_document_path,
                    'notes' => $transaction->notes,
                    'category' => $transaction->category,
                    'created_at' => $transaction->created_at,
                    'updated_at' => $transaction->updated_at,
                ]);
            } else {
                // Insert into buku_kas_kebun (BKK)
                DB::table('buku_kas_kebun')->insert([
                    'transaction_date' => $transaction->transaction_date,
                    'transaction_number' => 'BKK-' . $transaction->transaction_number,
                    'transaction_type' => $transaction->transaction_type,
                    'amount' => $transaction->amount,
                    'source_destination' => $transaction->source_destination,
                    'received_by' => $transaction->received_by,
                    'proof_document_path' => $transaction->proof_document_path,
                    'notes' => $transaction->notes,
                    'category' => $transaction->category,
                    'kp_id' => null, // Will be set later when KP→BKK auto-create is implemented
                    'created_at' => $transaction->created_at,
                    'updated_at' => $transaction->updated_at,
                ]);
            }
        }
    }

    /**
     * Determine if a transaction belongs to KP (Keuangan Perusahaan) or BKK (Buku Kas Kebun)
     */
    private function isKPTransaction($transaction)
    {
        // KP categories: Company-level financial transactions
        $kpCategories = [
            'Sales Revenue',
            'Personnel Cost',
            'Administrative Cost',
            'Financial Cost',
            'Tax',
            'Investment',
            'Loan',
            'Other Income',
            'Other Expense'
        ];
        
        // BKK categories: Garden/plantation-level operational transactions
        $bkkCategories = [
            'Operational Cost',
            'Maintenance Cost',
            'Logistics Cost',
            'Harvesting Cost',
            'Fertilizer Cost',
            'Pest Control Cost',
            'Transportation Cost',
            'Fuel Cost'
        ];
        
        // Check if category is in KP list
        if (in_array($transaction->category, $kpCategories)) {
            return true;
        }
        
        // Check if category is in BKK list
        if (in_array($transaction->category, $bkkCategories)) {
            return false;
        }
        
        // Default logic: Income transactions usually go to KP, operational expenses to BKK
        return $transaction->transaction_type === 'income';
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove all migrated data from both tables
        DB::table('keuangan_perusahaan')->truncate();
        DB::table('buku_kas_kebun')->truncate();
    }
};