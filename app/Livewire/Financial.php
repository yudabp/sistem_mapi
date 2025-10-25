<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FinancialTransaction as FinancialTransactionModel;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Imports\FinancialImport;
use App\Exports\FinancialExportWithHeaders;
use App\Exports\FinancialPdfExporter;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class Financial extends Component
{
    use WithFileUploads;

    public $transaction_date;
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

    public function mount()
    {
        $this->loadTransactions();
        // Set default filter dates: start date 1 month ago, end date today in DD-MM-YYYY format
        if (!$this->startDate) {
            $this->startDate = now()->subMonth()->format('d-m-Y');
        }
        if (!$this->endDate) {
            $this->endDate = now()->format('d-m-Y');
        }
        
        // Set default export dates: start date 1 month ago, end date today in DD-MM-YYYY format
        if (!$this->exportStartDate) {
            $this->exportStartDate = now()->subMonth()->format('d-m-Y');
        }
        if (!$this->exportEndDate) {
            $this->exportEndDate = now()->format('d-m-Y');
        }
    }

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
        'importFile' => 'required|file|mimes:xlsx,xls,csv',
    ];

    public function render()
    {
        $filteredTransactions = $this->filterTransactions();
        
        return view('livewire.financial', [
            'transactions' => $filteredTransactions,
            'total_income' => $this->getTotalIncome(),
            'total_expenses' => $this->getTotalExpenses(),
            'balance' => $this->getBalance(),
        ]);
    }

    public function saveTransaction()
    {
        $validated = $this->validate();
        
        // Convert date from DD-MM-YYYY to YYYY-MM-DD format for database storage
        $dateForDb = \DateTime::createFromFormat('d-m-Y', $this->transaction_date)->format('Y-m-d');
        
        // Handle file upload
        $proofPath = null;
        if ($this->proof_document) {
            $proofPath = $this->proof_document->store('financial_proofs', 'public');
        }

        FinancialTransactionModel::create([
            'transaction_date' => $dateForDb,
            'transaction_number' => 'TXN' . date('Ymd') . rand(1000, 9999), // Generate transaction number
            'transaction_type' => $this->transaction_type,
            'amount' => $this->amount,
            'source_destination' => $this->source_destination,
            'received_by' => $this->received_by,
            'proof_document_path' => $proofPath,
            'notes' => $this->notes,
            'category' => $this->category,
        ]);

        // Reset form
        $this->resetForm();
        $this->loadTransactions();
        
        $this->setPersistentMessage('Financial transaction created successfully.', 'success');
    }

    public function resetForm()
    {
        $this->transaction_date = date('d-m-Y'); // Format for DD-MM-YYYY display
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
        $this->transactions = FinancialTransactionModel::orderBy('transaction_date', 'desc')->get();
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
                    $startDate = \DateTime::createFromFormat('d-m-Y', $this->startDate)->format('Y-m-d');
                    $endDate = \DateTime::createFromFormat('d-m-Y', $this->endDate)->format('Y-m-d');
                    
                    $transactions = $transactions->filter(function ($item) use ($startDate, $endDate) {
                        return $item->transaction_date->between(
                            \Carbon\Carbon::parse($startDate),
                            \Carbon\Carbon::parse($endDate)
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
        $transaction = FinancialTransactionModel::find($id);
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
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $transaction = FinancialTransactionModel::find($id);
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
        $transaction = FinancialTransactionModel::find($this->deletingTransactionId);
        if ($transaction) {
            // Delete the proof if it exists
            if ($transaction->proof_document_path) {
                Storage::disk('public')->delete($transaction->proof_document_path);
            }
            $transaction->delete();
            $this->loadTransactions();
            $this->setPersistentMessage('Financial transaction deleted successfully.', 'success');
        }
        
        $this->closeDeleteConfirmation();
    }

    public function saveTransactionModal()
    {
        if ($this->isEditing) {
            $this->updateTransaction();
        } else {
            $this->saveTransaction();
        }
        
        $this->closeCreateModal();
    }

    public function updateTransaction()
    {
        $validated = $this->validate();
        
        // Convert date from DD-MM-YYYY to YYYY-MM-DD format for database storage
        $dateForDb = \DateTime::createFromFormat('d-m-Y', $this->transaction_date)->format('Y-m-d');
        
        $transaction = FinancialTransactionModel::find($this->editingId);
        if ($transaction) {
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

            $this->setPersistentMessage('Financial transaction updated successfully.', 'success');
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
        $this->validate();
        
        try {
            Excel::import(new FinancialImport, $this->importFile);
            $this->setPersistentMessage('Financial transaction data imported successfully.', 'success');
            $this->closeImportModal();
        } catch (\Exception $e) {
            $this->setPersistentMessage('Error importing data: ' . $e->getMessage(), 'error');
        }
    }
    
    // Export methods
    public function exportToExcel()
    {
        $export = new FinancialExportWithHeaders($this->exportStartDate, $this->exportEndDate);
        
        $filename = 'financial_data_export_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download($export, $filename);
    }
    
    public function exportToPdf()
    {
        // Redirect to the dedicated PDF export controller route
        return redirect()->route('financial.export.pdf', [
            'start_date' => $this->exportStartDate,
            'end_date' => $this->exportEndDate,
        ]);
    }
}
