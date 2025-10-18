<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FinancialTransaction as FinancialTransactionModel;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

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
    
    protected $queryString = ['search', 'dateFilter', 'typeFilter'];

    protected $rules = [
        'transaction_date' => 'required|date',
        'transaction_type' => 'required|in:income,expense',
        'amount' => 'required|numeric',
        'purpose' => 'required',
    ];

    public function mount()
    {
    }

    public function render()
    {
        $filteredTransactions = $this->filterTransactions();

        return view('livewire.cash-book', [
            'transactions' => $filteredTransactions,
            'total_income' => $filteredTransactions->where('transaction_type', 'income')->sum('amount'),
            'total_expenses' => $filteredTransactions->where('transaction_type', 'expense')->sum('amount'),
            'balance' => $filteredTransactions->where('transaction_type', 'income')->sum('amount') - $filteredTransactions->where('transaction_type', 'expense')->sum('amount'),
        ]);
    }

    public function saveTransaction()
    {
        $validated = $this->validate();
        
        // Handle file upload
        $proofPath = null;
        if ($this->proof_document) {
            $proofPath = $this->proof_document->store('cashbook_proofs', 'public');
        }

        FinancialTransactionModel::create([
            'transaction_date' => $this->transaction_date,
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
        
        session()->flash('message', 'Cash book transaction created successfully.');
    }

    public function resetForm()
    {
        $this->transaction_date = date('Y-m-d');
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

        return $query->get();
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
}
