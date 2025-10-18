<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale as SaleModel;
use App\Models\Production as ProductionModel;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Sales extends Component
{
    use WithFileUploads;

    public $sp_number;
    public $tbs_quantity;
    public $kg_quantity;
    public $price_per_kg;
    public $total_amount;
    public $sales_proof;
    public $sale_date;
    public $customer_name;
    public $customer_address;
    
    public $search = '';
    public $dateFilter = '';
    
    protected $queryString = ['search', 'dateFilter'];

    protected $rules = [
        'sp_number' => 'required',
        'kg_quantity' => 'required|numeric',
        'price_per_kg' => 'required|numeric',
        'sale_date' => 'required|date',
        'customer_name' => 'required',
        'customer_address' => 'required',
        'sales_proof' => 'nullable|image|max:10240', // Max 10MB
    ];

    public function mount()
    {
    }

    public function render()
    {
        $filteredSales = $this->filterSales();

        return view('livewire.sales', [
            'sales' => $filteredSales,
            'total_kg' => $filteredSales->sum('kg_quantity'),
            'total_sales' => $filteredSales->sum('total_amount'),
        ]);
    }

    public function updatedPricePerKg()
    {
        if ($this->kg_quantity && $this->price_per_kg) {
            $this->total_amount = $this->kg_quantity * $this->price_per_kg;
        }
    }

    public function updatedKgQuantity()
    {
        if ($this->kg_quantity && $this->price_per_kg) {
            $this->total_amount = $this->kg_quantity * $this->price_per_kg;
        }
    }

    public function saveSales()
    {
        $validated = $this->validate();
        
        // Handle file upload
        $proofPath = null;
        if ($this->sales_proof) {
            $proofPath = $this->sales_proof->store('sales_proofs', 'public');
        }

        SaleModel::create([
            'sp_number' => $this->sp_number,
            'tbs_quantity' => $this->tbs_quantity,
            'kg_quantity' => $this->kg_quantity,
            'price_per_kg' => $this->price_per_kg,
            'total_amount' => $this->total_amount,
            'sales_proof_path' => $proofPath,
            'sale_date' => $this->sale_date,
            'customer_name' => $this->customer_name,
            'customer_address' => $this->customer_address,
        ]);

        // Reset form
        $this->resetForm();
        
        session()->flash('message', 'Sales record created successfully.');
    }

    public function resetForm()
    {
        $this->sp_number = '';
        $this->tbs_quantity = '';
        $this->kg_quantity = '';
        $this->price_per_kg = '';
        $this->total_amount = '';
        $this->sales_proof = null;
        $this->sale_date = '';
        $this->customer_name = '';
        $this->customer_address = '';
    }

    public function filterSales()
    {
        $query = SaleModel::orderBy('sale_date', 'desc');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('sp_number', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_address', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->dateFilter) {
            $query->whereYear('sale_date', '=', substr($this->dateFilter, 0, 4))
                  ->whereMonth('sale_date', '=', substr($this->dateFilter, 5, 2));
        }

        return $query->get();
    }

    public function deleteSales($id)
    {
        $sale = SaleModel::find($id);
        if ($sale) {
            // Delete the proof if it exists
            if ($sale->sales_proof_path) {
                Storage::disk('public')->delete($sale->sales_proof_path);
            }
            $sale->delete();
        }
    }
}
