<?php

namespace App\Observers;

use App\Models\KeuanganPerusahaan;
use App\Models\BukuKasKebun;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class KeuanganPerusahaanObserver
{
    /**
     * Handle the KeuanganPerusahaan "created" event.
     *
     * @param  \App\Models\KeuanganPerusahaan  $keuanganPerusahaan
     * @return void
     */
    public function created(KeuanganPerusahaan $keuanganPerusahaan)
    {
        // Only auto-create BKK entry for KP expense transactions
        if ($keuanganPerusahaan->transaction_type === 'expense') {
            $this->autoCreateBkkEntry($keuanganPerusahaan);
        }
    }

    /**
     * Auto-create BKK income entry when KP expense is created
     *
     * @param  \App\Models\KeuanganPerusahaan  $kpTransaction
     * @return void
     */
    private function autoCreateBkkEntry(KeuanganPerusahaan $kpTransaction)
    {
        try {
            DB::beginTransaction();

            // Create BKK income entry
            $bkkTransaction = BukuKasKebun::create([
                'transaction_date' => $kpTransaction->transaction_date,
                'transaction_number' => 'BKK-AUTO-' . date('Ymd') . rand(1000, 9999),
                'transaction_type' => 'income', // BKK receives income when KP has expense
                'amount' => $kpTransaction->amount,
                'source_destination' => 'Keuangan Perusahaan (Auto-generated)',
                'received_by' => $kpTransaction->received_by,
                'proof_document_path' => $kpTransaction->proof_document_path,
                'notes' => 'Auto-generated from KP transaction #' . $kpTransaction->transaction_number . '. ' . $kpTransaction->notes,
                'category' => $this->mapKpCategoryToBkkCategory($kpTransaction->category),
                'kp_id' => $kpTransaction->id,
            ]);

            DB::commit();

            Log::info('Auto-created BKK entry for KP transaction', [
                'kp_id' => $kpTransaction->id,
                'kp_transaction_number' => $kpTransaction->transaction_number,
                'bkk_id' => $bkkTransaction->id,
                'bkk_transaction_number' => $bkkTransaction->transaction_number,
                'amount' => $kpTransaction->amount,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to auto-create BKK entry for KP transaction', [
                'kp_id' => $kpTransaction->id,
                'kp_transaction_number' => $kpTransaction->transaction_number,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Re-throw the exception to ensure the user knows something went wrong
            throw $e;
        }
    }

    /**
     * Map KP category to BKK category
     *
     * @param  string  $kpCategory
     * @return string
     */
    private function mapKpCategoryToBkkCategory($kpCategory)
    {
        $categoryMapping = [
            // KP categories that map to BKK operational categories
            'Personnel Cost' => 'Operational Cost',
            'Administrative Cost' => 'Operational Cost',
            'Financial Cost' => 'Operational Cost',
            'Investment' => 'Operational Cost',
            'Other Expense' => 'Operational Cost',
            
            // Default mapping
            'default' => 'Other Income',
        ];

        return $categoryMapping[$kpCategory] ?? $categoryMapping['default'];
    }
}