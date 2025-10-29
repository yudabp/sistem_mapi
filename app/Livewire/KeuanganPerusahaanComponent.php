<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\KeuanganPerusahaan;
use App\Models\BukuKasKebun;
use App\Services\FinancialTransactionService;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Imports\KeuanganPerusahaanImport;
use App\Exports\KeuanganPerusahaanExportWithHeaders;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use App\Livewire\Concerns\WithRoleCheck;

class KeuanganPerusahaanComponent extends Component
{
    use WithFileUploads;
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
    
    public $transactions = [];
    public $search = '';
    public $dateFilter = '';
    public $typeFilter = '';

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
    
    // Related BKK transactions
    public $showRelatedBkk = false;
    public $relatedBkkTransactions;
    public $selectedKpId = null;
    
    // Expense confirmation
    public $showExpenseConfirmation = false;
    
    // Import/Export properties
    public $importFile = null;
    public $exportStartDate = null;
    public $exportEndDate = null;
    public $showImportModal = false;
    
    protected $rules = [
        'transaction_date' => 'required|date_format:d-m-Y',
        'transaction_type' => 'required|in:income,expense',
        'amount' => 'required|numeric',
        'source_destination' => 'required',
        'category' => 'required',
        'importFile' => 'nullable|file|mimes:xlsx,xls,csv', // Make importFile nullable for regular form operations
    ];

    public function mount()
    {
        $this->mountWithRoleCheck();
        $this->loadTransactions();
        $this->relatedBkkTransactions = collect();
        
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

    public function render()
    {
        $filteredTransactions = $this->filterTransactions();
        
        return view('livewire.keuangan-perusahaan', [
            'transactions' => $filteredTransactions,
            'total_income' => $this->getTotalIncome(),
            'total_expenses' => $this->getTotalExpenses(),
            'balance' => $this->getBalance(),
        ]);
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

        // Use the financial transaction service to create KP with auto BKK
        $result = $this->getFinancialService()->createKpWithAutoBkk([
            'transaction_date' => $dateForDb,
            'transaction_type' => $this->transaction_type,
            'amount' => $this->amount,
            'source_destination' => $this->source_destination,
            'received_by' => $this->received_by,
            'proof_document_path' => $proofPath,
            'notes' => $this->notes,
            'category' => $this->category,
        ]);

        if ($result['success']) {
            // Reset form
            $this->resetForm();
            $this->loadTransactions();
            
            $message = $result['message'];
            if ($result['bkk_transaction']) {
                $message .= ' BKK entry #' . $result['bkk_transaction']->transaction_number . ' was auto-created.';
            }
            
            $this->setPersistentMessage($message, 'success');
        } else {
            $this->setPersistentMessage($result['message'], 'error');
        }
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
    }

    public function loadTransactions()
    {
        $this->transactions = KeuanganPerusahaan::orderBy('transaction_date', 'desc')->get();
    }

    public function filterTransactions()
    {
        $transactions = $this->transactions;

        if ($this->search) {
            $transactions = $transactions->filter(function ($item) {
                return stripos($item->transaction_number, $this->search) !== false ||
                       stripos($item->source_destination, $this->search) !== false ||
                       stripos($item->received_by, $this->search) !== false ||
                       stripos($item->category, $this->search) !== false;
            });
        }

        if ($this->dateFilter) {
            $transactions = $transactions->filter(function ($item) {
                return $item->transaction_date->format('Y-m') === $this->dateFilter;
            });
        }

        if ($this->typeFilter) {
            $transactions = $transactions->filter(function ($item) {
                return $item->transaction_type === $this->typeFilter;
            });
        }

        // Apply metric filter
        $transactions = $this->applyMetricFilter($transactions);

        return $transactions;
    }

    public function applyMetricFilter($transactions)
    {
        $now = now();
        
        switch ($this->metricFilter) {
            case 'today':
                $transactions = $transactions->filter(function ($item) use ($now) {
                    return $item->transaction_date->toDateString() === $now->toDateString();
                });
                break;
            case 'yesterday':
                $yesterday = $now->copy()->subDay();
                $transactions = $transactions->filter(function ($item) use ($yesterday) {
                    return $item->transaction_date->toDateString() === $yesterday->toDateString();
                });
                break;
            case 'this_week':
                $startOfWeek = $now->copy()->startOfWeek();
                $endOfWeek = $now->copy()->endOfWeek();
                $transactions = $transactions->filter(function ($item) use ($startOfWeek, $endOfWeek) {
                    return $item->transaction_date->between($startOfWeek, $endOfWeek);
                });
                break;
            case 'last_week':
                $lastWeekStart = $now->copy()->subWeek()->startOfWeek();
                $lastWeekEnd = $now->copy()->subWeek()->endOfWeek();
                $transactions = $transactions->filter(function ($item) use ($lastWeekStart, $lastWeekEnd) {
                    return $item->transaction_date->between($lastWeekStart, $lastWeekEnd);
                });
                break;
            case 'this_month':
                $transactions = $transactions->filter(function ($item) use ($now) {
                    return $item->transaction_date->year === $now->year && 
                           $item->transaction_date->month === $now->month;
                });
                break;
            case 'last_month':
                $lastMonth = $now->copy()->subMonth();
                $transactions = $transactions->filter(function ($item) use ($lastMonth) {
                    return $item->transaction_date->year === $lastMonth->year && 
                           $item->transaction_date->month === $lastMonth->month;
                });
                break;
            case 'custom':
                if ($this->startDate && $this->endDate) {
                    $transactions = $transactions->filter(function ($item) {
                        return $item->transaction_date->between(
                            \Carbon\Carbon::parse($this->startDate),
                            \Carbon\Carbon::parse($this->endDate)
                        );
                    });
                }
                break;
            case 'all':
            default:
                // No additional filtering for 'all' option
                break;
        }
        
        return $transactions;
    }

    public function getTotalIncome()
    {
        $transactions = $this->applyMetricFilter($this->transactions);
        return $transactions->where('transaction_type', 'income')->sum('amount');
    }

    public function getTotalExpenses()
    {
        $transactions = $this->applyMetricFilter($this->transactions);
        return $transactions->where('transaction_type', 'expense')->sum('amount');
    }

    public function getBalance()
    {
        return $this->getTotalIncome() - $this->getTotalExpenses();
    }

    public function deleteTransaction($id)
    {
        $transaction = KeuanganPerusahaan::find($id);
        if ($transaction) {
            // Delete the proof if it exists
            if ($transaction->proof_document_path) {
                Storage::disk('public')->delete($transaction->proof_document_path);
            }
            $transaction->delete();
            $this->loadTransactions();
        }
    }

    // Modal methods
    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        
        // Show confirmation dialog for expense transactions
        if ($this->transaction_type === 'expense') {
            $this->showExpenseConfirmation = true;
        } else {
            $this->showModal = true;
        }
    }

    public function openEditModal($id)
    {
        $transaction = KeuanganPerusahaan::find($id);
        if ($transaction) {
            $this->editingId = $transaction->id;
            $this->transaction_date = $transaction->transaction_date->format('d-m-Y'); // Format for DD-MM-YYYY display
            $this->transaction_type = $transaction->transaction_type;
            $this->amount = $transaction->amount;
            $this->source_destination = $transaction->source_destination;
            $this->received_by = $transaction->received_by;
            $this->notes = $transaction->notes;
            $this->category = $transaction->category;
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
    
    public function closeExpenseConfirmation()
    {
        $this->showExpenseConfirmation = false;
    }
    
    public function confirmExpenseCreation()
    {
        $this->showExpenseConfirmation = false;
        $this->showModal = true;
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
        $transaction = KeuanganPerusahaan::find($this->deletingTransactionId);
        if ($transaction) {
            // Delete the proof if it exists
            if ($transaction->proof_document_path) {
                Storage::disk('public')->delete($transaction->proof_document_path);
            }
            $transaction->delete();
            $this->loadTransactions();
            $this->setPersistentMessage('Keuangan Perusahaan transaction deleted successfully.', 'success');
        }
        
        $this->closeDeleteConfirmation();
    }

    public function saveTransactionModal()
    {
        try {
            if ($this->isEditing) {
                $this->updateTransaction();
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
        
        $transaction = KeuanganPerusahaan::find($this->editingId);
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
            ]);

            $this->setPersistentMessage('Keuangan Perusahaan transaction updated successfully.', 'success');
            $this->loadTransactions();
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
        $this->persistentMessage = $message;
        $this->messageType = $type;
    }

    public function clearPersistentMessage()
    {
        $this->persistentMessage = '';
    }
    
    /**
     * Show related BKK transactions for a KP transaction
     */
    public function showRelatedBkkTransactions($kpId)
    {
        $this->selectedKpId = $kpId;
        $this->relatedBkkTransactions = $this->getFinancialService()->getRelatedBkkTransactions($kpId);
        $this->showRelatedBkk = true;
    }
    
    /**
     * Hide related BKK transactions
     */
    public function hideRelatedBkkTransactions()
    {
        $this->showRelatedBkk = false;
        $this->relatedBkkTransactions = collect();
        $this->selectedKpId = null;
    }
    
    /**
     * Check if a BKK transaction was auto-generated
     */
    public function isAutoGeneratedBkk($bkkTransaction)
    {
        return $this->getFinancialService()->isAutoGeneratedBkk($bkkTransaction->id);
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
            $import = new KeuanganPerusahaanImport();
            Excel::import($import, $this->importFile);
            
            $this->setPersistentMessage('Keuangan Perusahaan transaction data imported successfully.', 'success');
            $this->closeImportModal();
            $this->loadTransactions(); // Refresh the transaction list after import
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
            ['transaction_date', 'transaction_type', 'amount', 'source_destination', 'received_by', 'notes', 'category'],
            [now()->format('d-m-Y'), 'income', '15000000', 'Customer Payment', 'Budi Santoso', 'Pembayaran penjualan bulan ini', 'Sales Revenue'],
            [now()->format('d-m-Y'), 'expense', '5000000', 'Supplier', 'Siti Aminah', 'Pembelian bahan baku', 'Operational Cost'],
            [now()->format('d-m-Y'), 'expense', '3000000', 'Transport Company', 'Ahmad Fauzi', 'Biaya transportasi', 'Logistics Cost'],
        ];
        
        $csv = '';
        foreach ($sampleData as $row) {
            $csv .= '"' . implode('","', $row) . "\"\n";
        }
        
        // Save to a temporary file
        $filename = 'sample_keuangan_perusahaan_data.csv';
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
        $export = new KeuanganPerusahaanExportWithHeaders($this->exportStartDate, $this->exportEndDate);
        
        $filename = 'keuangan_perusahaan_data_export_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download($export, $filename);
    }
    
    public function exportToPdf()
    {
        $this->authorizeView();
        // Redirect to the dedicated PDF export controller route
        return redirect()->route('financial.export.pdf', [
            'start_date' => $this->exportStartDate,
            'end_date' => $this->exportEndDate,
        ]);
    }
}
