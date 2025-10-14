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
    
    public $debts = [];
    public $search = '';
    public $statusFilter = '';
    
    protected $rules = [
        'amount' => 'required|numeric',
        'creditor' => 'required',
        'due_date' => 'required|date',
        'description' => 'required',
    ];

    public function mount()
    {
        $this->loadDebts();
    }

    public function render()
    {
        return view('livewire.debts', [
            'debts' => $this->filterDebts(),
            'total_debt' => $this->debts->where('status', 'unpaid')->sum('amount'),
            'paid_amount' => $this->debts->where('status', 'paid')->sum('amount'),
            'remaining_debt' => $this->debts->where('status', 'unpaid')->sum('amount'),
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
        $this->loadDebts();
        
        session()->flash('message', 'Debt record created successfully.');
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

    public function loadDebts()
    {
        $this->debts = DebtModel::orderBy('due_date', 'desc')->get();
    }

    public function filterDebts()
    {
        $debts = $this->debts;

        if ($this->search) {
            $debts = $debts->filter(function ($item) {
                return stripos($item->creditor, $this->search) !== false ||
                       stripos($item->description, $this->search) !== false;
            });
        }

        if ($this->statusFilter) {
            $debts = $debts->filter(function ($item) {
                return $item->status === $this->statusFilter;
            });
        }

        return $debts;
    }

    public function markAsPaid($id)
    {
        $debt = DebtModel::find($id);
        if ($debt) {
            $debt->status = 'paid';
            $debt->paid_date = now();
            $debt->save();
            $this->loadDebts();
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
            $this->loadDebts();
        }
    }
}
