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
    
    public $sales = [];
    public $search = '';
    public $dateFilter = '';
    
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
        $this->loadSales();
    }

    public function render()
    {
        return view('livewire.sales', [
            'sales' => $this->filterSales(),
            'total_kg' => $this->sales->sum('kg_quantity'),
            'total_sales' => $this->sales->sum('total_amount'),
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
        $this->loadSales();
        
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

    public function loadSales()
    {
        $this->sales = SaleModel::orderBy('sale_date', 'desc')->get();
    }

    public function filterSales()
    {
        $sales = $this->sales;

        if ($this->search) {
            $sales = $sales->filter(function ($item) {
                return stripos($item->sp_number, $this->search) !== false ||
                       stripos($item->customer_name, $this->search) !== false ||
                       stripos($item->customer_address, $this->search) !== false;
            });
        }

        if ($this->dateFilter) {
            $sales = $sales->filter(function ($item) {
                return $item->sale_date->format('Y-m') === $this->dateFilter;
            });
        }

        return $sales;
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
            $this->loadSales();
        }
    }
}
