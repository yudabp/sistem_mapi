<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Debt as DebtModel;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

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

    protected $rules = [
        'amount' => 'required|numeric',
        'creditor' => 'required',
        'due_date' => 'required|date',
        'description' => 'required',
    ];

    public function mount()
    {
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
        
        // Handle file upload
        $proofPath = null;
        if ($this->proof_document) {
            $proofPath = $this->proof_document->store('debt_proofs', 'public');
        }

        DebtModel::create([
            'amount' => $this->amount,
            'creditor' => $this->creditor,
            'due_date' => $this->due_date,
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
            $this->due_date = $debt->due_date->format('Y-m-d');
            $this->description = $debt->description;
            $this->proof_document = null; // We don't load the file, just the path
            $this->status = $debt->status;
            $this->paid_date = $debt->paid_date ? $debt->paid_date->format('Y-m-d') : null;
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
                'due_date' => $this->due_date,
                'description' => $this->description,
                'proof_document_path' => $proofPath,
                'status' => $this->status,
                'paid_date' => $this->paid_date,
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
}
