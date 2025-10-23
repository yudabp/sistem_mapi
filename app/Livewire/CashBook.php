<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FinancialTransaction as FinancialTransactionModel;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Imports\CashBookImport;
use App\Exports\CashBookExportWithHeaders;
use App\Exports\CashBookPdfExporter;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class CashBook extends Component
{
    use WithFileUploads;

    public $transaction_date;
    public $transaction_type;
    public $amount;
    public $purpose;
    public $description;
    public $proof_document;
    public $notes;
    
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
    
    protected $queryString = ['search', 'dateFilter', 'typeFilter', 'metricFilter'];

    protected $rules = [
        'transaction_date' => 'required|date_format:d-m-Y',
        'transaction_type' => 'required|in:income,expense',
        'amount' => 'required|numeric',
        'purpose' => 'required',
        'importFile' => 'required|file|mimes:xlsx,xls,csv',
    ];

    public function mount()
    {
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

    public function render()
    {
        $filteredTransactions = $this->filterTransactions();

        return view('livewire.cash-book', [
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
            $proofPath = $this->proof_document->store('cashbook_proofs', 'public');
        }

        FinancialTransactionModel::create([
            'transaction_date' => $dateForDb,
            'transaction_number' => 'CB' . date('Ymd') . rand(1000, 9999), // Generate transaction number for cash book
            'transaction_type' => $this->transaction_type,
            'amount' => $this->amount,
            'source_destination' => $this->purpose, // Using purpose as source/destination for cash book
            'notes' => $this->notes,
            'category' => 'Cash Book',
            'proof_document_path' => $proofPath,
        ]);

        // Reset form
        $this->resetForm();
        
        $this->setPersistentMessage('Cash book transaction created successfully.', 'success');
    }

    public function resetForm()
    {
        $this->transaction_date = date('d-m-Y'); // Format for DD-MM-YYYY display
        $this->transaction_type = 'income';
        $this->amount = '';
        $this->purpose = '';
        $this->description = '';
        $this->proof_document = null;
        $this->notes = '';
    }

    public function filterTransactions()
    {
        $query = FinancialTransactionModel::where('category', 'Cash Book')
            ->orderBy('transaction_date', 'desc');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('transaction_number', 'like', '%' . $this->search . '%')
                  ->orWhere('source_destination', 'like', '%' . $this->search . '%')
                  ->orWhere('notes', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->dateFilter) {
            $query->whereYear('transaction_date', '=', substr($this->dateFilter, 0, 4))
                  ->whereMonth('transaction_date', '=', substr($this->dateFilter, 5, 2));
        }

        if ($this->typeFilter) {
            $query->where('transaction_type', '=', $this->typeFilter);
        }

        // Apply metric filter
        $query = $this->applyMetricFilter($query);

        return $query->get();
    }

    public function applyMetricFilter($query)
    {
        $now = now();
        
        switch ($this->metricFilter) {
            case 'today':
                $query->whereDate('transaction_date', $now->toDateString());
                break;
            case 'yesterday':
                $yesterday = $now->subDay();
                $query->whereDate('transaction_date', $yesterday->toDateString());
                break;
            case 'this_week':
                $query->whereBetween('transaction_date', [
                    $now->startOfWeek()->toDateString(),
                    $now->endOfWeek()->toDateString()
                ]);
                break;
            case 'last_week':
                $lastWeekStart = $now->subWeek()->startOfWeek();
                $lastWeekEnd = $now->subWeek()->endOfWeek();
                $query->whereBetween('transaction_date', [
                    $lastWeekStart->toDateString(),
                    $lastWeekEnd->toDateString()
                ]);
                break;
            case 'this_month':
                $query->whereYear('transaction_date', $now->year)
                      ->whereMonth('transaction_date', $now->month);
                break;
            case 'last_month':
                $lastMonth = $now->subMonth();
                $query->whereYear('transaction_date', $lastMonth->year)
                      ->whereMonth('transaction_date', $lastMonth->month);
                break;
            case 'custom':
                if ($this->startDate && $this->endDate) {
                    $startDate = \DateTime::createFromFormat('d-m-Y', $this->startDate)->format('Y-m-d');
                    $endDate = \DateTime::createFromFormat('d-m-Y', $this->endDate)->format('Y-m-d');
                    $query->whereBetween('transaction_date', [$startDate, $endDate]);
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
        $query = FinancialTransactionModel::where('category', 'Cash Book');
        $query = $this->applyMetricFilter($query);
        return $query->where('transaction_type', 'income')->sum('amount');
    }

    public function getTotalExpenses()
    {
        $query = FinancialTransactionModel::where('category', 'Cash Book');
        $query = $this->applyMetricFilter($query);
        return $query->where('transaction_type', 'expense')->sum('amount');
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
            $this->purpose = $transaction->source_destination;
            $this->notes = $transaction->notes;
            $this->proof_document = null; // We don't load the file, just the path
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
            $this->setPersistentMessage('Cash book transaction deleted successfully.', 'success');
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
                $proofPath = $this->proof_document->store('cashbook_proofs', 'public');
            }

            $transaction->update([
                'transaction_date' => $dateForDb,
                'transaction_type' => $this->transaction_type,
                'amount' => $this->amount,
                'source_destination' => $this->purpose,
                'notes' => $this->notes,
                'proof_document_path' => $proofPath,
            ]);

            $this->setPersistentMessage('Cash book transaction updated successfully.', 'success');
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
    public $importFile = null;
    public $exportStartDate = null;
    public $exportEndDate = null;
    public $showImportModal = false;
    
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
            Excel::import(new CashBookImport, $this->importFile);
            $this->setPersistentMessage('Cash book data imported successfully.', 'success');
            $this->closeImportModal();
        } catch (\Exception $e) {
            $this->setPersistentMessage('Error importing data: ' . $e->getMessage(), 'error');
        }
    }
    
    // Export methods
    public function exportToExcel()
    {
        $export = new CashBookExportWithHeaders($this->exportStartDate, $this->exportEndDate);
        
        $filename = 'cash_book_data_export_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download($export, $filename);
    }
    
    public function exportToPdf()
    {
        // Redirect to the dedicated PDF export controller route
        return redirect()->route("cashbook.export.pdf", [
            "start_date" => $this->exportStartDate,
            "end_date" => $this->exportEndDate,
        ]);
    }
}
