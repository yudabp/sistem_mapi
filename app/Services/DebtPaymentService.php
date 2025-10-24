<?php

namespace App\Services;

use App\Models\Debt;
use App\Models\BukuKasKebun;
use App\Models\MasterBkkExpenseCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class DebtPaymentService
{
    /**
     * Process a debt payment and create corresponding BKK transaction
     *
     * @param array $paymentData
     * @return array
     * @throws Exception
     */
    public function processPayment(array $paymentData): array
    {
        try {
            return DB::transaction(function () use ($paymentData) {
                $debt = Debt::findOrFail($paymentData['debt_id']);

                // Validate payment amount
                if ($paymentData['payment_amount'] > $debt->sisa_hutang) {
                    throw new Exception('Payment amount cannot exceed remaining debt');
                }

                // Create debt payment record
                $debtPaymentId = DB::table('hutang_pembayaran')->insertGetId([
                    'debt_id' => $paymentData['debt_id'],
                    'payment_amount' => $paymentData['payment_amount'],
                    'payment_date' => $paymentData['payment_date'],
                    'payment_method' => $paymentData['payment_method'] ?? null,
                    'reference_number' => $paymentData['reference_number'] ?? null,
                    'notes' => $paymentData['notes'] ?? null,
                    'proof_document_path' => $paymentData['proof_document_path'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Get debt payment category
                $debtPaymentCategory = MasterBkkExpenseCategory::debtPayment()
                    ->active()
                    ->firstOrFail();

                // Generate BKK transaction number
                $transactionNumber = $this->generateBkkTransactionNumber($paymentData['payment_date']);

                // Create BKK expense transaction
                $bkkTransaction = BukuKasKebun::create([
                    'transaction_date' => $paymentData['payment_date'],
                    'transaction_number' => $transactionNumber,
                    'transaction_type' => 'expense',
                    'amount' => $paymentData['payment_amount'],
                    'source_destination' => $debt->creditor,
                    'received_by' => $paymentData['received_by'] ?? null,
                    'proof_document_path' => $paymentData['proof_document_path'] ?? null,
                    'notes' => $paymentData['notes'] ?? 'Pembayaran hutang: ' . $debt->description,
                    'category' => $debtPaymentCategory->name,
                    'expense_category_id' => $debtPaymentCategory->id,
                    'debt_id' => $debt->id,
                ]);

                // Link BKK transaction to debt payment
                DB::table('hutang_pembayaran')->where('id', $debtPaymentId)->update(['bkk_transaction_id' => $bkkTransaction->id]);

                // Update debt remaining balance
                $newRemainingBalance = $debt->sisa_hutang - $paymentData['payment_amount'];

                // Update debt status and paid date if fully paid
                if ($newRemainingBalance <= 0) {
                    $debt->update([
                        'sisa_hutang' => 0,
                        'status' => 'paid',
                        'paid_date' => $paymentData['payment_date'],
                    ]);
                } else {
                    $debt->update(['sisa_hutang' => $newRemainingBalance]);
                }

                Log::info('Debt payment processed successfully', [
                    'debt_id' => $debt->id,
                    'payment_amount' => $paymentData['payment_amount'],
                    'remaining_balance' => $newRemainingBalance,
                    'bkk_transaction_id' => $bkkTransaction->id,
                ]);

                return [
                    'success' => true,
                    'debt_payment_id' => $debtPaymentId,
                    'bkk_transaction' => $bkkTransaction,
                    'updated_debt' => $debt->fresh(),
                ];
            });
        } catch (Exception $e) {
            Log::error('Failed to process debt payment', [
                'payment_data' => $paymentData,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Get unpaid debts for dropdown selection
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUnpaidDebts()
    {
        return Debt::with(['debtType'])
            ->where('status', 'unpaid')
            ->where('sisa_hutang', '>', 0)
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Get debt payment history
     *
     * @param int $debtId
     * @return \Illuminate\Support\Collection
     */
    public function getPaymentHistory(int $debtId)
    {
        return DB::table('hutang_pembayaran')
            ->leftJoin('buku_kas_kebun', 'hutang_pembayaran.bkk_transaction_id', '=', 'buku_kas_kebun.id')
            ->where('hutang_pembayaran.debt_id', $debtId)
            ->orderBy('hutang_pembayaran.payment_date', 'desc')
            ->select('hutang_pembayaran.*', 'buku_kas_kebun.transaction_number')
            ->get();
    }

    /**
     * Generate BKK transaction number
     *
     * @param string $date
     * @return string
     */
    private function generateBkkTransactionNumber(string $date): string
    {
        $datePrefix = date('Ymd', strtotime($date));
        $maxAttempts = 10; // Prevent infinite loop
        $attempt = 0;

        do {
            $attempt++;

            // Get the last transaction number for the date (handle both old and new formats)
            $lastTransaction = BukuKasKebun::whereDate('transaction_date', $date)
                ->where(function($query) use ($datePrefix) {
                    $query->where('transaction_number', 'like', 'BKK' . $datePrefix . '%')
                          ->orWhere('transaction_number', 'like', 'BKK-' . $datePrefix . '%');
                })
                ->orderBy('transaction_number', 'desc')
                ->first();

            if ($lastTransaction) {
                // Extract the numeric part from different formats
                if (strpos($lastTransaction->transaction_number, 'BKK-' . $datePrefix . '-') === 0) {
                    // New format: BKK-YYYYMMDD-XXXX
                    $lastNumber = intval(substr($lastTransaction->transaction_number, -4));
                } elseif (strpos($lastTransaction->transaction_number, 'BKK' . $datePrefix) === 0) {
                    // Old format: BKKYYYYMMDDXXXX
                    $lastNumber = intval(substr($lastTransaction->transaction_number, -4));
                } else {
                    // Fallback: try to extract last 4 digits
                    $lastNumber = intval(substr($lastTransaction->transaction_number, -4));
                }
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $transactionNumber = 'BKK-' . $datePrefix . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            // Check if this transaction number already exists
            $exists = BukuKasKebun::where('transaction_number', $transactionNumber)->exists();

            if (!$exists) {
                return $transactionNumber;
            }

        } while ($exists && $attempt < $maxAttempts);

        // If we reach here, generate with microtimestamp to ensure uniqueness
        return 'BKK-' . $datePrefix . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT) . '-' . uniqid();
    }

    /**
     * Get debt summary statistics
     *
     * @return array
     */
    public function getDebtSummaryStats(): array
    {
        $totalDebts = Debt::count();
        $unpaidDebts = Debt::where('status', 'unpaid')->count();
        $paidDebts = Debt::where('status', 'paid')->count();
        $totalDebtAmount = Debt::sum('amount');
        $totalPaidAmount = \DB::table('hutang_pembayaran')->sum('payment_amount');
        $totalRemainingAmount = Debt::where('status', 'unpaid')->sum('sisa_hutang');

        return [
            'total_debts' => $totalDebts,
            'unpaid_debts' => $unpaidDebts,
            'paid_debts' => $paidDebts,
            'total_debt_amount' => $totalDebtAmount,
            'total_paid_amount' => $totalPaidAmount,
            'total_remaining_amount' => $totalRemainingAmount,
            'payment_percentage' => $totalDebtAmount > 0 ? ($totalPaidAmount / $totalDebtAmount) * 100 : 0,
        ];
    }

    /**
     * Get upcoming due debts
     *
     * @param int $days
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUpcomingDueDebts(int $days = 30)
    {
        return Debt::with(['debtType'])
            ->where('status', 'unpaid')
            ->where('sisa_hutang', '>', 0)
            ->whereBetween('due_date', [now(), now()->addDays($days)])
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Get overdue debts
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOverdueDebts()
    {
        return Debt::with(['debtType'])
            ->where('status', 'unpaid')
            ->where('sisa_hutang', '>', 0)
            ->where('due_date', '<', now())
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Reverse a debt payment (admin function)
     *
     * @param int $paymentId
     * @return array
     * @throws Exception
     */
    public function reversePayment(int $paymentId): array
    {
        try {
            return DB::transaction(function () use ($paymentId) {
                $payment = HutangPembayaran::with(['debt', 'bkkTransaction'])->findOrFail($paymentId);
                $debt = $payment->debt;

                // Delete BKK transaction if exists
                if ($payment->bkkTransaction) {
                    $payment->bkkTransaction->delete();
                }

                // Update debt remaining balance
                $newRemainingBalance = $debt->sisa_hutang + $payment->payment_amount;

                // Update debt status if it was previously fully paid
                $debt->update([
                    'sisa_hutang' => $newRemainingBalance,
                    'status' => 'unpaid',
                    'paid_date' => null,
                ]);

                // Delete payment record
                $payment->delete();

                Log::info('Debt payment reversed successfully', [
                    'payment_id' => $paymentId,
                    'debt_id' => $debt->id,
                    'reversed_amount' => $payment->payment_amount,
                    'new_remaining_balance' => $newRemainingBalance,
                ]);

                return [
                    'success' => true,
                    'updated_debt' => $debt->fresh(),
                ];
            });
        } catch (Exception $e) {
            Log::error('Failed to reverse debt payment', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}