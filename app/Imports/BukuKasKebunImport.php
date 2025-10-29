<?php

namespace App\Imports;

use App\Models\BukuKasKebun;
use App\Models\KeuanganPerusahaan;
use App\Models\Debt;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Auth;
use DateTime;

class BukuKasKebunImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * Collection of rows to be imported
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Convert date from DD-MM-YYYY to Y-m-d format
            $date = $row['transaction_date'];
            $dateObj = DateTime::createFromFormat('d-m-Y', $date);
            if (!$dateObj) {
                $dateObj = DateTime::createFromFormat('Y-m-d', $date);
            }
            $formattedDate = $dateObj ? $dateObj->format('Y-m-d') : null;

            // Validate and get related KP transaction if provided
            $kpId = null;
            if (!empty($row['kp_id'])) {
                $kpTransaction = KeuanganPerusahaan::find($row['kp_id']);
                if ($kpTransaction) {
                    $kpId = $kpTransaction->id;
                }
            }

            // Validate and get related Debt if provided
            $debtId = null;
            if (!empty($row['debt_id'])) {
                $debt = Debt::find($row['debt_id']);
                if ($debt) {
                    $debtId = $debt->id;
                }
            }

            // Generate transaction number if not provided
            $transactionNumber = $row['transaction_number'] ?? 'BKK' . date('Ymd') . rand(1000, 9999);

            BukuKasKebun::create([
                'transaction_date' => $formattedDate,
                'transaction_number' => $transactionNumber,
                'transaction_type' => $row['transaction_type'],
                'amount' => $row['amount'],
                'source_destination' => $row['source_destination'],
                'received_by' => $row['received_by'] ?? null,
                'notes' => $row['notes'] ?? null,
                'category' => $row['category'],
                'kp_id' => $kpId,
                'debt_id' => $debtId,
                'created_by' => Auth::id(), // Assuming there's a created_by field
            ]);
        }
    }

    /**
     * Validation rules for the import
     */
    public function rules(): array
    {
        return [
            'transaction_date' => 'required|date_format:d-m-Y|before_or_equal:today',
            'transaction_type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'source_destination' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'received_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'kp_id' => 'nullable|exists:keuangan_perusahaan,id',
            'debt_id' => 'nullable|exists:debts,id',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'transaction_date.required' => 'The transaction date is required.',
            'transaction_date.date_format' => 'The transaction date must be in DD-MM-YYYY format.',
            'transaction_date.before_or_equal' => 'The transaction date cannot be in the future.',
            'transaction_type.required' => 'The transaction type is required.',
            'transaction_type.in' => 'The transaction type must be either income or expense.',
            'amount.required' => 'The amount is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be at least 0.01.',
            'source_destination.required' => 'The source/destination is required.',
            'category.required' => 'The category is required.',
            'kp_id.exists' => 'The selected KP transaction does not exist.',
            'debt_id.exists' => 'The selected debt does not exist.',
        ];
    }
}