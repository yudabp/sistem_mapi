<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Debt as DebtModel;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Imports\DebtsImport;
use App\Exports\DebtsExportWithHeaders;
use App\Exports\DebtsPdfExporter;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Debts extends Component
{
    use WithFileUploads;

    public $amount;
    public $creditor;
    public $due_date;
    public $description;
    public $proof_document;
    public $status;
    public $paid_date;
    
    public $search = '';
    public $statusFilter = '';

    // Modal control
    public $showModal = false;
    public $isEditing = false;
    public $editingId = null;

    // Delete confirmation
    public $showDeleteConfirmation = false;
    public $deletingDebtId = null;
    public $deletingDebtName = '';

    // Photo viewing
    public $showPhotoModal = false;
    public $photoToView = null;

    // Persistent message
    public $persistentMessage = '';
    public $messageType = 'success'; // success, error, warning, info
    
    protected $queryString = ['search', 'statusFilter'];

    public $importFile = null;
    public $exportStartDate = null;
    public $exportEndDate = null;
    public $showImportModal = false;

    protected $rules = [
        'amount' => 'required|numeric',
        'creditor' => 'required',
        'due_date' => 'required|date_format:d-m-Y',
        'description' => 'required',
        'importFile' => 'required|file|mimes:xlsx,xls,csv',
    ];

    public function mount()
    {
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
        $filteredDebts = $this->filterDebts();

        return view('livewire.debts', [
            'debts' => $filteredDebts,
            'total_debt' => $filteredDebts->where('status', 'unpaid')->sum('amount'),
            'paid_amount' => $filteredDebts->where('status', 'paid')->sum('amount'),
            'remaining_debt' => $filteredDebts->where('status', 'unpaid')->sum('amount'),
        ]);
    }

    public function saveDebt()
    {
        $validated = $this->validate();
        
        // Convert date from DD-MM-YYYY to YYYY-MM-DD format for database storage
        $dueDateForDb = \DateTime::createFromFormat('d-m-Y', $this->due_date)->format('Y-m-d');
        
        // Handle file upload
        $proofPath = null;
        if ($this->proof_document) {
            $proofPath = $this->proof_document->store('debt_proofs', 'public');
        }

        DebtModel::create([
            'amount' => $this->amount,
            'creditor' => $this->creditor,
            'due_date' => $dueDateForDb,
            'description' => $this->description,
            'proof_document_path' => $proofPath,
            'status' => 'unpaid', // Default to unpaid
            'paid_date' => null,
        ]);

        // Reset form
        $this->resetForm();
        
        $this->setPersistentMessage('Debt record created successfully.', 'success');
    }

    public function resetForm()
    {
        $this->amount = '';
        $this->creditor = '';
        $this->due_date = '';
        $this->description = '';
        $this->proof_document = null;
        $this->status = 'unpaid';
        $this->paid_date = '';
    }

    public function filterDebts()
    {
        $query = DebtModel::orderBy('due_date', 'desc');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('creditor', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', '=', $this->statusFilter);
        }

        return $query->get();
    }

    public function markAsPaid($id)
    {
        $debt = DebtModel::find($id);
        if ($debt) {
            $debt->status = 'paid';
            $debt->paid_date = now();
            $debt->save();
        }
    }

    public function deleteDebt($id)
    {
        $debt = DebtModel::find($id);
        if ($debt) {
            // Delete the proof if it exists
            if ($debt->proof_document_path) {
                Storage::disk('public')->delete($debt->proof_document_path);
            }
            $debt->delete();
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
        $debt = DebtModel::find($id);
        if ($debt) {
            $this->editingId = $debt->id;
            $this->amount = $debt->amount;
            $this->creditor = $debt->creditor;
            $this->due_date = $debt->due_date->format('d-m-Y'); // Format for DD-MM-YYYY display
            $this->description = $debt->description;
            $this->proof_document = null; // We don't load the file, just the path
            $this->status = $debt->status;
            $this->paid_date = $debt->paid_date ? $debt->paid_date->format('d-m-Y') : null; // Format for DD-MM-YYYY display
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

    public function confirmDelete($id, $creditor)
    {
        $this->deletingDebtId = $id;
        $this->deletingDebtName = $creditor;
        $this->showDeleteConfirmation = true;
    }

    public function closeDeleteConfirmation()
    {
        $this->showDeleteConfirmation = false;
        $this->deletingDebtId = null;
        $this->deletingDebtName = '';
    }

    public function deleteDebtConfirmed()
    {
        $debt = DebtModel::find($this->deletingDebtId);
        if ($debt) {
            // Delete the proof if it exists
            if ($debt->proof_document_path) {
                Storage::disk('public')->delete($debt->proof_document_path);
            }
            $debt->delete();
            $this->setPersistentMessage('Debt record deleted successfully.', 'success');
        }
        
        $this->closeDeleteConfirmation();
    }

    public function saveDebtModal()
    {
        if ($this->isEditing) {
            $this->updateDebt();
        } else {
            $this->saveDebt();
        }
        
        $this->closeCreateModal();
    }

    public function updateDebt()
    {
        $validated = $this->validate();
        
        // Convert dates from DD-MM-YYYY to YYYY-MM-DD format for database storage
        $dueDateForDb = \DateTime::createFromFormat('d-m-Y', $this->due_date)->format('Y-m-d');
        $paidDateForDb = $this->paid_date ? \DateTime::createFromFormat('d-m-Y', $this->paid_date)->format('Y-m-d') : null;
        
        $debt = DebtModel::find($this->editingId);
        if ($debt) {
            // Handle file upload
            $proofPath = $debt->proof_document_path; // Keep existing path if no new file
            if ($this->proof_document) {
                // Delete old proof if exists
                if ($debt->proof_document_path) {
                    Storage::disk('public')->delete($debt->proof_document_path);
                }
                $proofPath = $this->proof_document->store('debt_proofs', 'public');
            }

            $debt->update([
                'amount' => $this->amount,
                'creditor' => $this->creditor,
                'due_date' => $dueDateForDb,
                'description' => $this->description,
                'proof_document_path' => $proofPath,
                'status' => $this->status,
                'paid_date' => $paidDateForDb,
            ]);

            $this->setPersistentMessage('Debt record updated successfully.', 'success');
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
    
    public function importDebt()
    {
        $this->validate();
        
        try {
            Excel::import(new DebtsImport, $this->importFile);
            $this->setPersistentMessage('Debt data imported successfully.', 'success');
            $this->closeImportModal();
        } catch (\Exception $e) {
            $this->setPersistentMessage('Error importing data: ' . $e->getMessage(), 'error');
        }
    }
    
    // Export methods
    public function exportToExcel()
    {
        $export = new DebtsExportWithHeaders($this->exportStartDate, $this->exportEndDate);
        
        $filename = 'debt_data_export_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download($export, $filename);
    }
    
    public function exportToPdf()
    {
        $exporter = new DebtsPdfExporter($this->exportStartDate, $this->exportEndDate);
        
        $html = $exporter->generate();
        
        // Ensure proper UTF-8 encoding with fallback
        if (function_exists('mb_convert_encoding')) {
            $html = mb_convert_encoding($html, 'UTF-8', 'auto');
        } else {
            $html = utf8_encode($html);
        }
        
        $filename = 'debt_data_export_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        // Create DomPDF instance
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return response()->make($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
