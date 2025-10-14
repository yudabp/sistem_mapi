<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FinancialTransaction;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class BukuKas extends Component
{
    use WithFileUploads;

    public $transaction_date;
    public $transaction_type;
    public $amount;
    public $purpose;
    public $description;
    public $proof_document;
    public $notes;
    public $category;
    
    public $transactions = [];
    public $search = '';
    public $dateFilter = '';
    public $typeFilter = '';
    
    protected $rules = [
        'transaction_date' => 'required|date',
        'transaction_type' => 'required|in:income,expense',
        'amount' => 'required|numeric',
        'purpose' => 'required',
        'category' => 'required',
    ];

    public function mount()
    {
        $this->transaction_date = date('Y-m-d');
        $this->transaction_type = 'income';
        $this->loadTransactions();
    }

    public function render()
    {
        return view('livewire.buku-kas', [
            'transactions' => $this->filterTransactions(),
            'total_income' => $this->transactions->where('transaction_type', 'income')->sum('amount'),
            'total_expenses' => $this->transactions->where('transaction_type', 'expense')->sum('amount'),
            'balance' => $this->transactions->where('transaction_type', 'income')->sum('amount') - $this->transactions->where('transaction_type', 'expense')->sum('amount'),
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

        FinancialTransaction::create([
            'transaction_date' => $this->transaction_date,
            'transaction_number' => 'CB' . date('Ymd') . rand(1000, 9999), // Generate transaction number for cash book
            'transaction_type' => $this->transaction_type,
            'amount' => $this->amount,
            'source_destination' => $this->purpose, // Using purpose as source/destination for cash book
            'notes' => $this->notes,
            'category' => $this->category,
            'proof_document_path' => $proofPath,
        ]);

        // Reset form
        $this->resetForm();
        $this->loadTransactions();
        
        session()->flash('message', 'Transaksi buku kas berhasil disimpan.');
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
        $this->category = '';
    }

    public function loadTransactions()
    {
        $this->transactions = FinancialTransaction::where('category', 'Cash Book')
            ->orderBy('transaction_date', 'desc')
            ->get();
    }

    public function filterTransactions()
    {
        $transactions = $this->transactions;

        if ($this->search) {
            $transactions = $transactions->filter(function ($item) {
                return stripos($item->transaction_number, $this->search) !== false ||
                       stripos($item->source_destination, $this->search) !== false ||
                       stripos($item->notes, $this->search) !== false;
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

        return $transactions;
    }

    public function deleteTransaction($id)
    {
        $transaction = FinancialTransaction::find($id);
        if ($transaction) {
            // Delete the proof if it exists
            if ($transaction->proof_document_path) {
                Storage::disk('public')->delete($transaction->proof_document_path);
            }
            $transaction->delete();
            $this->loadTransactions();
        }
    }
}
