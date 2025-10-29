<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Debt;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Livewire\Concerns\WithRoleCheck;

class DataHutang extends Component
{
    use WithFileUploads;
    use WithRoleCheck;

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

    // Metric filter
    public $metricFilter = 'all'; // Default to all time
    public $startDate = null;
    public $endDate = null;

    // Persistent message
    public $persistentMessage = '';
    public $messageType = 'success'; // success, error, warning, info

    protected $queryString = ['search', 'statusFilter', 'metricFilter'];

    protected $rules = [
        'amount' => 'required|numeric',
        'creditor' => 'required',
        'due_date' => 'required|date',
        'description' => 'required',
    ];

    public function mount()
    {
        $this->mountWithRoleCheck();
    }

    public function render()
    {
        $filteredDebts = $this->filterDebts();

        return view('livewire.data-hutang', [
            'debts' => $filteredDebts,
            'total_debt' => $this->getTotalDebt(),
            'paid_amount' => $this->getPaidAmount(),
            'remaining_debt' => $this->getRemainingDebt(),
        ]);
    }

    public function saveDebt()
    {
        $this->authorizeEdit();
        $validated = $this->validate();
        
        // Handle file upload
        $proofPath = null;
        if ($this->proof_document) {
            $proofPath = $this->proof_document->store('debt_proofs', 'public');
        }

        Debt::create([
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
        
        $this->setPersistentMessage('Data hutang berhasil disimpan.', 'success');
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
        $query = Debt::orderBy('due_date', 'desc');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('creditor', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', '=', $this->statusFilter);
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
                $query->whereDate('due_date', $now->toDateString());
                break;
            case 'yesterday':
                $yesterday = $now->subDay();
                $query->whereDate('due_date', $yesterday->toDateString());
                break;
            case 'this_week':
                $query->whereBetween('due_date', [
                    $now->startOfWeek()->toDateString(),
                    $now->endOfWeek()->toDateString()
                ]);
                break;
            case 'last_week':
                $lastWeekStart = $now->subWeek()->startOfWeek();
                $lastWeekEnd = $now->subWeek()->endOfWeek();
                $query->whereBetween('due_date', [
                    $lastWeekStart->toDateString(),
                    $lastWeekEnd->toDateString()
                ]);
                break;
            case 'this_month':
                $query->whereYear('due_date', $now->year)
                      ->whereMonth('due_date', $now->month);
                break;
            case 'last_month':
                $lastMonth = $now->subMonth();
                $query->whereYear('due_date', $lastMonth->year)
                      ->whereMonth('due_date', $lastMonth->month);
                break;
            case 'custom':
                if ($this->startDate && $this->endDate) {
                    $query->whereBetween('due_date', [$this->startDate, $this->endDate]);
                }
                break;
            case 'all':
            default:
                // No additional filtering for 'all' option
                break;
        }
        
        return $query;
    }

    public function getTotalDebt()
    {
        $query = Debt::query();
        $query = $this->applyMetricFilter($query);
        return $query->where('status', 'unpaid')->sum('amount');
    }

    public function getPaidAmount()
    {
        $query = Debt::query();
        $query = $this->applyMetricFilter($query);
        return $query->where('status', 'paid')->sum('amount');
    }

    public function getRemainingDebt()
    {
        $query = Debt::query();
        $query = $this->applyMetricFilter($query);
        return $query->where('status', 'unpaid')->sum('amount');
    }

    public function markAsPaid($id)
    {
        $debt = Debt::find($id);
        if ($debt) {
            $debt->status = 'paid';
            $debt->paid_date = now();
            $debt->save();
        }
    }

    public function deleteDebt($id)
    {
        $debt = Debt::find($id);
        if ($debt) {
            // Delete the proof if it exists
            if ($debt->proof_document_path) {
                Storage::disk('public')->delete($debt->proof_document_path);
            }
            $debt->delete();
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $debt = Debt::find($id);
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
        $this->authorizeDelete();
        $debt = Debt::find($this->deletingDebtId);
        if ($debt) {
            // Delete the proof if it exists
            if ($debt->proof_document_path) {
                Storage::disk('public')->delete($debt->proof_document_path);
            }
            $debt->delete();
            $this->setPersistentMessage('Data hutang berhasil dihapus.', 'success');
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
        $this->authorizeEdit();
        $validated = $this->validate();
        
        $debt = Debt::find($this->editingId);
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

            $this->setPersistentMessage('Data hutang berhasil diperbarui.', 'success');
        }
    }

    public function showPhoto($path)
    {
        $this->photoToView = $path;
        $this->showPhotoModal = true;
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
