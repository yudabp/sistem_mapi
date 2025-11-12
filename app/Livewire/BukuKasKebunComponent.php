<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BukuKasKebun;
use App\Models\KeuanganPerusahaan;
use App\Models\Debt;
use App\Models\MasterBkkExpenseCategory;
use App\Services\FinancialTransactionService;
use App\Services\DebtPaymentService;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Imports\BukuKasKebunImport;
use App\Exports\BukuKasKebunExportWithHeaders;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use App\Livewire\Concerns\WithRoleCheck;

class BukuKasKebunComponent extends Component
{
    use WithFileUploads;
    use WithPagination;
    use WithRoleCheck;

    public $transaction_date; // This will hold the DD-MM-YYYY format from the view
    public $transaction_date_formatted; // This will hold the YYYY-MM-DD format for processing
    public $transaction_type;
    public $amount;
    public $source_destination;
    public $received_by;
    public $proof_document;
    public $notes;
    public $category;
    public $kp_id; // For linking to KP transaction

    // Debt payment fields
    public $is_debt_payment = false;
    public $debt_id = null;
    public $payment_method = '';
    public $reference_number = '';
    public $unpaid_debts = [];
    public $debt_categories = [];

    // Autocomplete fields
    public $debt_search = '';
    public $debt_suggestions = [];
    public $show_debt_suggestions = false;
    public $selected_debt = null;

    public $search = '';
    public $dateFilter = '';
    public $typeFilter = '';
    public $perPage = 10;

    // Metric filter
    public $metricFilter = 'all'; // Default to all time
    public $startDate = null;
    public $endDate = null;

    // Modal control
    public $showModal = false;
    public $isEditing = false;
    public $editingId = null;

    // Delete confirmation
    public $showDeleteConfirmation = false;
    public $deletingTransactionId = null;
    public $deletingTransactionName = '';

    // Photo viewing
    public $showPhotoModal = false;
    public $photoToView = null;

    // Persistent message
    public $persistentMessage = '';
    public $messageType = 'success'; // success, error, warning, info
    
    // Related KP transaction
    public $showRelatedKp = false;
    public $relatedKpTransaction = null;
    public $selectedBkkId = null;
    
    // Import/Export properties
    public $importFile = null;
    public $exportStartDate = null;
    public $exportEndDate = null;
    public $showImportModal = false;
    
    /**
     * Get the validation rules that apply to the request.
     */
    protected function rules()
    {
        $rules = [
            'transaction_date' => 'required|date_format:d-m-Y',
            'transaction_type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'source_destination' => 'required',
            'category' => 'required',
            'debt_id' => 'nullable|exists:debts,id',
            'payment_method' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',
        ];

        // Additional rules for debt payment mode
        if ($this->is_debt_payment) {
            $rules['debt_id'] = 'required|exists:debts,id';
            $rules['amount'] = 'required|numeric|min:0.01';
        }

        return $rules;
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->is_debt_payment && $this->debt_id) {
                $debt = \DB::table('debts')->find($this->debt_id);
                if ($debt && $this->amount > $debt->sisa_hutang) {
                    $validator->errors()->add('amount', 'Payment amount cannot exceed remaining debt (Rp ' . number_format($debt->sisa_hutang, 2, ',', '.') . ')');
                }
            }
        });

        return $validator;
    }

    /**
     * Get the custom error messages for the defined validation rules.
     */
    protected function messages()
    {
        return [
            'debt_id.required' => 'Please select a debt to pay.',
            'debt_id.exists' => 'Selected debt does not exist.',
            'amount.required' => 'Payment amount is required.',
            'amount.numeric' => 'Payment amount must be a number.',
            'amount.min' => 'Payment amount must be at least Rp 0.01.',
            'transaction_date.required' => 'Transaction date is required.',
            'transaction_date.date' => 'Please enter a valid date.',
            'transaction_type.required' => 'Transaction type is required.',
            'transaction_type.in' => 'Transaction type must be either income or expense.',
            'source_destination.required' => 'Source/Destination is required.',
            'category.required' => 'Category is required.',
        ];
    }

    public function mount()
    {
        $this->mountWithRoleCheck();
        $this->loadUnpaidDebts();
        $this->loadDebtCategories();

        // Set default export dates: start date 1 month ago, end date today in DD-MM-YYYY format
        if (!$this->exportStartDate) {
            $this->exportStartDate = now()->subMonth()->format('d-m-Y');
        }
        if (!$this->exportEndDate) {
            $this->exportEndDate = now()->format('d-m-Y');
        }
    }
    
    /**
     * Get the financial transaction service
     */
    private function getFinancialService()
    {
        return app(FinancialTransactionService::class);
    }

    /**
     * Get the debt payment service
     */
    private function getDebtPaymentService()
    {
        return app(DebtPaymentService::class);
    }

    public function render()
    {
        $filteredTransactions = $this->filterTransactions();
        
        return view('livewire.buku-kas-kebun', [
            'transactions' => $filteredTransactions,
            'total_income' => $this->getTotalIncome(),
            'total_expenses' => $this->getTotalExpenses(),
            'balance' => $this->getBalance(),
        ]);
    }

    /**
     * Load unpaid debts for dropdown
     */
    public function loadUnpaidDebts()
    {
        $this->unpaid_debts = $this->getDebtPaymentService()->getUnpaidDebts();
    }

    /**
     * Load debt payment categories
     */
    public function loadDebtCategories()
    {
        $this->debt_categories = MasterBkkExpenseCategory::debtPayment()
            ->active()
            ->get();
    }

    /**
     * Handle debt payment toggle
     */
    public function updatedIsDebtPayment($value)
    {
        if ($value) {
            $this->transaction_type = 'expense';

            // Auto-select debt payment category
            $debtCategory = $this->debt_categories->first();
            if ($debtCategory) {
                $this->category = $debtCategory->name;
            }
        } else {
            $this->debt_id = null;
            $this->payment_method = '';
            $this->reference_number = '';
        }
    }

    /**
     * Handle debt selection
     */
    public function updatedDebtId($value)
    {
        if ($value) {
            $debt = Debt::find($value);
            if ($debt) {
                $this->amount = min($debt->sisa_hutang, $debt->cicilan_per_bulan ?? $debt->sisa_hutang);
                $this->source_destination = $debt->creditor;
                $this->notes = 'Pembayaran hutang: ' . $debt->description;
            }
        }
    }

    /**
     * Validate debt payment data
     */
    public function validateDebtPayment()
    {
        $rules = [
            'debt_id' => 'required|exists:debts,id',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'payment_method' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',
        ];

        $messages = [
            'debt_id.required' => 'Please select a debt to pay.',
            'debt_id.exists' => 'Selected debt does not exist.',
            'amount.required' => 'Payment amount is required.',
            'amount.numeric' => 'Payment amount must be a number.',
            'amount.min' => 'Payment amount must be at least Rp 0.01.',
            'transaction_date.required' => 'Transaction date is required.',
            'transaction_date.date' => 'Please enter a valid date.',
        ];

        $validator = \Validator::make([
            'debt_id' => $this->debt_id,
            'amount' => $this->amount,
            'transaction_date' => $this->transaction_date,
            'payment_method' => $this->payment_method,
            'reference_number' => $this->reference_number,
        ], $rules, $messages);

        // Custom validation: amount should not exceed remaining debt
        $validator->after(function ($validator) {
            if ($this->debt_id) {
                $debt = \DB::table('debts')->find($this->debt_id);
                if ($debt && $this->amount > $debt->sisa_hutang) {
                    $validator->errors()->add('amount', 'Payment amount cannot exceed remaining debt (Rp ' . number_format($debt->sisa_hutang, 2, ',', '.') . ')');
                }
            }
        });

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return true;
    }

    /**
     * Process debt payment
     */
    public function processDebtPayment()
    {
        // Custom validation for debt payment
        $this->validateDebtPayment();

        try {
            // Convert date from DD-MM-YYYY to YYYY-MM-DD format for database storage
            $dateForDb = $this->transaction_date ? \DateTime::createFromFormat('d-m-Y', $this->transaction_date)->format('Y-m-d') : date('Y-m-d');
            
            $paymentData = [
                'debt_id' => $this->debt_id,
                'payment_amount' => $this->amount,
                'payment_date' => $dateForDb,
                'payment_method' => $this->payment_method,
                'reference_number' => $this->reference_number,
                'notes' => $this->notes,
                'received_by' => $this->received_by,
                'proof_document_path' => null, // Will be handled below
            ];

            // Handle file upload
            if ($this->proof_document) {
                $proofPath = $this->proof_document->store('financial_proofs', 'public');
                $paymentData['proof_document_path'] = $proofPath;
            }

            $result = $this->getDebtPaymentService()->processPayment($paymentData);

            $this->setPersistentMessage('Pembayaran hutang berhasil diproses!', 'success');
            $this->resetForm();
            // Transactions are loaded in render() method
            $this->loadUnpaidDebts();

        } catch (\Exception $e) {
            $this->setPersistentMessage('Error: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * Handle debt search autocomplete
     */
    public function updatedDebtSearch($value)
    {
        if (strlen($value) < 2) {
            $this->debt_suggestions = [];
            $this->show_debt_suggestions = false;
            return;
        }

        $this->debt_suggestions = \DB::table('debts')
            ->where('status', 'unpaid')
            ->where('sisa_hutang', '>', 0)
            ->where(function($query) use ($value) {
                $query->where('creditor', 'like', '%' . $value . '%')
                      ->orWhere('description', 'like', '%' . $value . '%');
            })
            ->orderBy('due_date')
            ->limit(10)
            ->get();

        $this->show_debt_suggestions = count($this->debt_suggestions) > 0;
    }

    /**
     * Select debt from autocomplete suggestions
     */
    public function selectDebt($debtId)
    {
        $debt = \DB::table('debts')->find($debtId);
        if ($debt) {
            $this->debt_id = $debt->id;
            $this->debt_search = $debt->creditor . ' - ' . $debt->description;
            $this->selected_debt = $debt;
            $this->show_debt_suggestions = false;

            // Auto-fill related fields
            $this->amount = min($debt->sisa_hutang, $debt->cicilan_per_bulan ?? $debt->sisa_hutang);
            $this->source_destination = $debt->creditor;
            $this->notes = 'Pembayaran hutang: ' . $debt->description;

            // Auto-select debt payment category
            $debtCategory = $this->debt_categories->first();
            if ($debtCategory) {
                $this->category = $debtCategory->name;
            }
        }
    }

    /**
     * Clear debt selection
     */
    public function clearDebtSelection()
    {
        $this->debt_id = null;
        $this->debt_search = '';
        $this->selected_debt = null;
        $this->show_debt_suggestions = false;
        $this->is_debt_payment = false;

        // Clear auto-filled fields but keep manual inputs
        $this->source_destination = '';
        $this->notes = '';
    }

    /**
     * Toggle debt payment mode
     */
    public function toggleDebtPayment()
    {
        $this->is_debt_payment = !$this->is_debt_payment;

        if ($this->is_debt_payment) {
            $this->transaction_type = 'expense';
            // Auto-select debt payment category
            $debtCategory = $this->debt_categories->first();
            if ($debtCategory) {
                $this->category = $debtCategory->name;
            }
        } else {
            $this->clearDebtSelection();
        }
    }

    /**
     * Close debt suggestions when clicking outside
     */
    public function closeDebtSuggestions()
    {
        $this->show_debt_suggestions = false;
    }

    public function saveTransaction()
    {
        $this->authorizeEdit();
        $validated = $this->validate();
        
        // Convert date from DD-MM-YYYY to YYYY-MM-DD format for database storage
        $dateForDb = $this->transaction_date ? \DateTime::createFromFormat('d-m-Y', $this->transaction_date)->format('Y-m-d') : date('Y-m-d');
        
        // Handle file upload
        $proofPath = null;
        if ($this->proof_document) {
            $proofPath = $this->proof_document->store('financial_proofs', 'public');
        }

        BukuKasKebun::create([
            'transaction_date' => $dateForDb,
            'transaction_number' => 'BKK' . date('Ymd') . rand(1000, 9999), // Generate transaction number
            'transaction_type' => $this->transaction_type,
            'amount' => $this->amount,
            'source_destination' => $this->source_destination,
            'received_by' => $this->received_by,
            'proof_document_path' => $proofPath,
            'notes' => $this->notes,
            'category' => $this->category,
            'kp_id' => $this->kp_id,
        ]);

        // Reset form
        $this->resetForm();
        // Transactions are loaded in render() method
        
        $this->setPersistentMessage('Buku Kas Kebun transaction created successfully.', 'success');
    }

    public function resetForm()
    {
        $this->transaction_date = date('d-m-Y');
        $this->transaction_type = 'income';
        $this->amount = '';
        $this->source_destination = '';
        $this->received_by = '';
        $this->proof_document = null;
        $this->notes = '';
        $this->category = '';
        $this->kp_id = null;

        // Reset debt payment fields
        $this->is_debt_payment = false;
        $this->debt_id = null;
        $this->payment_method = '';
        $this->reference_number = '';

        // Reset autocomplete fields
        $this->debt_search = '';
        $this->debt_suggestions = [];
        $this->show_debt_suggestions = false;
        $this->selected_debt = null;
    }

    public function filterTransactions()
    {
        $query = BukuKasKebun::orderBy('transaction_date', 'desc');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('transaction_number', 'like', '%' . $this->search . '%')
                  ->orWhere('source_destination', 'like', '%' . $this->search . '%')
                  ->orWhere('received_by', 'like', '%' . $this->search . '%')
                  ->orWhere('category', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->dateFilter) {
            $query->where('transaction_date', 'like', $this->dateFilter . '%');
        }

        if ($this->typeFilter) {
            $query->where('transaction_type', '=', $this->typeFilter);
        }

        // Apply metric filter
        $query = $this->applyMetricFilter($query);

        return $query->paginate($this->perPage);
    }

    public function applyMetricFilter($query)
    {
        $now = now();

        switch ($this->metricFilter) {
            case 'today':
                $query->whereDate('transaction_date', $now->toDateString());
                break;
            case 'yesterday':
                $yesterday = $now->copy()->subDay();
                $query->whereDate('transaction_date', $yesterday->toDateString());
                break;
            case 'this_week':
                $startOfWeek = $now->copy()->startOfWeek();
                $endOfWeek = $now->copy()->endOfWeek();
                $query->whereBetween('transaction_date', [$startOfWeek, $endOfWeek]);
                break;
            case 'last_week':
                $lastWeekStart = $now->copy()->subWeek()->startOfWeek();
                $lastWeekEnd = $now->copy()->subWeek()->endOfWeek();
                $query->whereBetween('transaction_date', [$lastWeekStart, $lastWeekEnd]);
                break;
            case 'this_month':
                $query->whereYear('transaction_date', $now->year)
                      ->whereMonth('transaction_date', $now->month);
                break;
            case 'last_month':
                $lastMonth = $now->copy()->subMonth();
                $query->whereYear('transaction_date', $lastMonth->year)
                      ->whereMonth('transaction_date', $lastMonth->month);
                break;
            case 'custom':
                if ($this->startDate && $this->endDate) {
                    $query->whereBetween('transaction_date', [
                        \Carbon\Carbon::parse($this->startDate),
                        \Carbon\Carbon::parse($this->endDate)
                    ]);
                }
                break;
            case 'all':
            default:
                // No additional filtering for 'all' option
                break;
        }

        return $query;
    }

    public function getTotalIncome()
    {
        $query = BukuKasKebun::query();
        $query = $this->applyMetricFilter($query);
        return $query->where('transaction_type', 'income')->sum('amount');
    }

    public function getTotalExpenses()
    {
        $query = BukuKasKebun::query();
        $query = $this->applyMetricFilter($query);
        return $query->where('transaction_type', 'expense')->sum('amount');
    }

    public function getBalance()
    {
        return $this->getTotalIncome() - $this->getTotalExpenses();
    }

    public function deleteTransaction($id)
    {
        $transaction = BukuKasKebun::find($id);
        if ($transaction) {
            // Delete the proof if it exists
            if ($transaction->proof_document_path) {
                Storage::disk('public')->delete($transaction->proof_document_path);
            }
            $transaction->delete();
            // Transactions are loaded in render() method
        }
    }

    // Modal methods
    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $transaction = BukuKasKebun::find($id);
        if ($transaction) {
            $this->editingId = $transaction->id;
            $this->transaction_date = $transaction->transaction_date->format('d-m-Y'); // Format for DD-MM-YYYY display
            $this->transaction_type = $transaction->transaction_type;
            $this->amount = $transaction->amount;
            $this->source_destination = $transaction->source_destination;
            $this->received_by = $transaction->received_by;
            $this->notes = $transaction->notes;
            $this->category = $transaction->category;
            $this->kp_id = $transaction->kp_id;
            $this->proof_document = null; // We don't load the file, just keep path reference
            $this->isEditing = true;
            $this->showModal = true;
        }
    }

    public function closeCreateModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->isEditing = false;
        $this->editingId = null;
    }

    public function confirmDelete($id, $transaction_number)
    {
        $this->deletingTransactionId = $id;
        $this->deletingTransactionName = $transaction_number;
        $this->showDeleteConfirmation = true;
    }

    public function closeDeleteConfirmation()
    {
        $this->showDeleteConfirmation = false;
        $this->deletingTransactionId = null;
        $this->deletingTransactionName = '';
    }

    public function deleteTransactionConfirmed()
    {
        $this->authorizeDelete();
        $transaction = BukuKasKebun::find($this->deletingTransactionId);
        if ($transaction) {
            // Delete the proof if it exists
            if ($transaction->proof_document_path) {
                Storage::disk('public')->delete($transaction->proof_document_path);
            }
            $transaction->delete();
            // Transactions are loaded in render() method
            $this->setPersistentMessage('Buku Kas Kebun transaction deleted successfully.', 'success');
        }
        
        $this->closeDeleteConfirmation();
    }

    public function saveTransactionModal()
    {
        try {
            if ($this->isEditing) {
                $this->updateTransaction();
            } elseif ($this->is_debt_payment) {
                $this->processDebtPayment();
            } else {
                $this->saveTransaction();
            }

            $this->closeCreateModal();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw the ValidationException so Livewire can automatically display errors
            // This will keep the modal open and show validation errors
            throw $e;
        } catch (\Exception $e) {
            $this->setPersistentMessage('Error: ' . $e->getMessage(), 'error');
            // Keep modal open so user can see the error
        }
    }

    public function updateTransaction()
    {
        $this->authorizeEdit();
        $validated = $this->validate();
        
        $transaction = BukuKasKebun::find($this->editingId);
        if ($transaction) {
            // Convert date from DD-MM-YYYY to YYYY-MM-DD format for database storage
            $dateForDb = \DateTime::createFromFormat('d-m-Y', $this->transaction_date)->format('Y-m-d');
            
            // Handle file upload
            $proofPath = $transaction->proof_document_path; // Keep existing path if no new file
            if ($this->proof_document) {
                // Delete old proof if exists
                if ($transaction->proof_document_path) {
                    Storage::disk('public')->delete($transaction->proof_document_path);
                }
                $proofPath = $this->proof_document->store('financial_proofs', 'public');
            }

            $transaction->update([
                'transaction_date' => $dateForDb,
                'transaction_type' => $this->transaction_type,
                'amount' => $this->amount,
                'source_destination' => $this->source_destination,
                'received_by' => $this->received_by,
                'proof_document_path' => $proofPath,
                'notes' => $this->notes,
                'category' => $this->category,
                'kp_id' => $this->kp_id,
            ]);

            $this->setPersistentMessage('Buku Kas Kebun transaction updated successfully.', 'success');
            // Transactions are loaded in render() method
        }
    }

    public function showPhoto($path)
    {
        $this->photoToView = $path;
        $this->showPhotoModal = true;
    }

    public function closePhotoModal()
    {
        $this->showPhotoModal = false;
        $this->photoToView = null;
    }

    public function setPersistentMessage($message, $type = 'success')
    {
        // Translate common messages to Indonesian
        $translations = [
            'Pembayaran hutang berhasil diproses!' => 'Pembayaran hutang berhasil diproses!',
            'Buku Kas Kebun transaction created successfully.' => 'Transaksi buku kas kebun berhasil ditambahkan.',
            'Buku Kas Kebun transaction deleted successfully.' => 'Transaksi buku kas kebun berhasil dihapus.',
            'Buku Kas Kebun transaction updated successfully.' => 'Transaksi buku kas kebun berhasil diperbarui.',
            'Buku Kas Kebun transaction data imported successfully.' => 'Data buku kas kebun berhasil diimpor.',
            'Error: ' => 'Terjadi kesalahan: ',
            'Error importing data: ' => 'Terjadi kesalahan saat mengimpor data: ',
            'Import failed with validation errors: ' => 'Impor gagal dengan kesalahan validasi: ',
        ];

        $this->persistentMessage = str_replace(array_keys($translations), array_values($translations), $message);
        $this->messageType = $type;
    }

    public function clearPersistentMessage()
    {
        $this->persistentMessage = '';
    }
    
    /**
     * Show related KP transaction for a BKK transaction
     */
    public function showRelatedKpTransaction($bkkId)
    {
        $this->selectedBkkId = $bkkId;
        $this->relatedKpTransaction = $this->getFinancialService()->getRelatedKpTransaction($bkkId);
        $this->showRelatedKp = true;
    }
    
    /**
     * Hide related KP transaction
     */
    public function hideRelatedKpTransaction()
    {
        $this->showRelatedKp = false;
        $this->relatedKpTransaction = null;
        $this->selectedBkkId = null;
    }
    
    /**
     * Check if a BKK transaction was auto-generated
     */
    public function isAutoGeneratedBkk($bkkTransaction)
    {
        return $this->getFinancialService()->isAutoGeneratedBkk($bkkTransaction->id);
    }

    /**
     * Check if a BKK transaction is a debt payment
     */
    public function isDebtPayment($transaction)
    {
        return $transaction->debt_id !== null ||
               ($transaction->expenseCategory && $transaction->expenseCategory->is_debt_payment);
    }

    /**
     * Get the related debt for a transaction
     */
    public function getRelatedDebt($transaction)
    {
        return $transaction->debt;
    }

    /**
     * Show debt payment details
     */
    public function showDebtPaymentDetails($transactionId)
    {
        $transaction = BukuKasKebun::find($transactionId);
        if ($transaction && $transaction->debt_id) {
            // This will be used in the frontend to show payment details
            $this->dispatch('showDebtPaymentDetails', [
                'transaction' => $transaction,
                'debt' => $transaction->debt,
                'payments' => $transaction->debt->payments ?? []
            ]);
        }
    }
    
    // Import methods
    public function openImportModal()
    {
        $this->showImportModal = true;
        $this->importFile = null;
    }
    
    public function closeImportModal()
    {
        $this->showImportModal = false;
        $this->importFile = null;
    }
    
    public function importTransaction()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,xls,csv',
        ]);
        
        try {
            $import = new BukuKasKebunImport();
            Excel::import($import, $this->importFile);
            
            $this->setPersistentMessage('Buku Kas Kebun transaction data imported successfully.', 'success');
            $this->closeImportModal();
            // Transactions are loaded in render() method // Refresh the transaction list after import
        } catch (ExcelValidationException $e) {
            $failureMessages = [];
            $failures = $e->failures();

            foreach ($failures as $failure) {
                // Ensure all error messages are UTF-8 clean
                $row = $failure->row();
                $errors = $failure->errors();
                $cleanErrors = array_map(function($error) {
                    return mb_convert_encoding($error, 'UTF-8', 'UTF-8');
                }, $errors);
                $failureMessages[] = 'Row ' . $row . ': ' . implode(', ', $cleanErrors);
            }

            $errorMessage = 'Import failed with validation errors: ' . implode(' | ', $failureMessages);
            // Ensure the error message is UTF-8 clean
            $cleanErrorMessage = mb_convert_encoding($errorMessage, 'UTF-8', 'UTF-8');
            $this->setPersistentMessage($cleanErrorMessage, 'error');
        } catch (\Exception $e) {
            // Ensure exception message is UTF-8 clean
            $cleanMessage = mb_convert_encoding($e->getMessage(), 'UTF-8', 'UTF-8');
            $this->setPersistentMessage('Error importing data: ' . $cleanMessage, 'error');
        }
    }
    
    public function downloadSampleExcel()
    {
        // Create a sample CSV file and store it temporarily
        $sampleData = [
            ['transaction_date', 'transaction_type', 'amount', 'source_destination', 'received_by', 'notes', 'category', 'kp_id'],
            [now()->format('d-m-Y'), 'income', '15000000', 'Customer Payment', 'Budi Santoso', 'Pembayaran penjualan bulan ini', 'Sales Revenue', ''],
            [now()->format('d-m-Y'), 'expense', '5000000', 'Supplier', 'Siti Aminah', 'Pembelian bahan baku', 'Operational Cost', ''],
            [now()->format('d-m-Y'), 'income', '8000000', 'Sales', 'Ahmad Fauzi', 'Penjualan produk', 'Sales Income', ''],
        ];
        
        $csv = '';
        foreach ($sampleData as $row) {
            $csv .= '"' . implode('","', $row) . "\"\n";
        }
        
        // Save to a temporary file
        $filename = 'sample_buku_kas_kebun_data.csv';
        $path = storage_path('app/temp/' . $filename);
        
        // Ensure the temp directory exists
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        file_put_contents($path, $csv);

        return response()->download($path)->deleteFileAfterSend(true);
    }
    
    // Export methods
    public function exportToExcel()
    {
        $export = new BukuKasKebunExportWithHeaders($this->exportStartDate, $this->exportEndDate);
        
        $filename = 'buku_kas_kebun_data_export_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download($export, $filename);
    }
    
    public function exportToPdf()
    {
        $this->authorizeView();
        // Redirect to the dedicated PDF export controller route
        return redirect()->route('cashbook.export.pdf', [
            'start_date' => $this->exportStartDate,
            'end_date' => $this->exportEndDate,
        ]);
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedDateFilter()
    {
        $this->resetPage();
    }

    public function updatedTransactionTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }
}
