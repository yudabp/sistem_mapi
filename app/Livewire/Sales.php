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

    protected $listeners = ['spChanged' => 'handleSpChanged'];

    public $sp_number;
    public $tbs_quantity;
    public $kg_quantity;
    public $price_per_kg;
    public $total_amount;
    public $sales_proof;
    public $sale_date;
    public $customer_name;
    public $customer_address;

    public $sp_numbers = [];
    public $is_fields_disabled = false;
    
    public $search = '';
    public $dateFilter = '';
    
    protected $queryString = ['search', 'dateFilter'];

    protected $rules = [
        'sp_number' => 'required',
        'kg_quantity' => 'required|numeric',
        'price_per_kg' => 'required|numeric',
        'sale_date' => 'required|date',
        'customer_name' => 'nullable|string|max:255',
        'customer_address' => 'nullable|string',
        'sales_proof' => 'nullable|image|max:10240', // Max 10MB
    ];

    public function mount()
    {
        $this->loadSPNumbers();
    }

    // Override the property to ensure it's always available
    public function getSpNumbersProperty()
    {
        if (empty($this->sp_numbers)) {
            $this->loadSPNumbers();
        }
        return $this->sp_numbers;
    }
    
    // Ensure the selected SP number is always available in the list
    public function ensureSelectedSpNumberInList()
    {
        if ($this->sp_number) {
            // Check if the currently selected SP number exists in the list
            $found = $this->sp_numbers->firstWhere('sp_number', $this->sp_number);
            
            if (!$found) {
                // If not found, we need to add it back to maintain selection
                $production = ProductionModel::where('sp_number', $this->sp_number)->first();
                if ($production) {
                    // Create a new collection that includes the missing SP number
                    $newCollection = $this->sp_numbers->concat([$production])->sortBy('sp_number')->values();
                    $this->sp_numbers = $newCollection;
                }
            }
        }
    }

    public function handleSpChanged()
    {
        logger('handleSpChanged called with sp_number: ' . $this->sp_number);
        $this->updatedSpNumber();
    }

    public function handleSpNumberChange($spNumber)
    {
        $this->sp_number = $spNumber;
        $this->updatedSpNumber();
    }

    public function updatedSpNumber()
    {
        // Debug log
        logger('updatedSpNumber called with: ' . $this->sp_number);

        // Reload SP numbers to ensure the selected one is always available
        $this->loadSPNumbers();

        if ($this->sp_number) {
            $production = ProductionModel::where('sp_number', $this->sp_number)->first();
            logger('Production found: ' . ($production ? 'Yes' : 'No'));

            if ($production) {
                logger('Setting fields - TBS: ' . $production->tbs_quantity . ', KG: ' . $production->kg_quantity);

                $this->tbs_quantity = $production->tbs_quantity;
                $this->kg_quantity = $production->kg_quantity;
                $this->is_fields_disabled = true;

                logger('Fields set - TBS: ' . $this->tbs_quantity . ', KG: ' . $this->kg_quantity . ', Disabled: ' . $this->is_fields_disabled);

                // Calculate total amount using common method
                $this->calculateTotalAmount();
            }
        } else {
            $this->tbs_quantity = '';
            $this->kg_quantity = '';
            $this->total_amount = '';
            $this->is_fields_disabled = false;
        }
    }

    public function loadSPNumbers()
    {
        // Get all SP numbers from production
        $allProductions = ProductionModel::select('sp_number', 'tbs_quantity', 'kg_quantity')
                                          ->orderBy('sp_number')
                                          ->get();

        // Get SP numbers that already have sales
        $soldSPNumbers = SaleModel::pluck('sp_number')->toArray();

        // Filter out sold SP numbers, but always keep the currently selected one if it exists
        $this->sp_numbers = $allProductions->filter(function($production) use ($soldSPNumbers) {
            // Always include if this is the currently selected SP number
            if ($this->sp_number && $production->sp_number == $this->sp_number) {
                return true;
            }
            // Otherwise, only include if it hasn't been sold yet
            return !in_array($production->sp_number, $soldSPNumbers);
        })->values();
    }

    public function render()
    {
        $filteredSales = $this->filterSales();

        // Ensure the currently selected SP number is in the list to prevent disappearing options
        if ($this->sp_number && !$this->sp_numbers->firstWhere('sp_number', $this->sp_number)) {
            $production = ProductionModel::where('sp_number', $this->sp_number)->first();
            if ($production) {
                // Add the selected SP number to the list to maintain selection in the UI
                $this->sp_numbers = $this->sp_numbers->concat([$production])->sortBy('sp_number')->values();
            }
        }

        return view('livewire.sales', [
            'sales' => $filteredSales,
            'total_kg' => $filteredSales->sum('kg_quantity'),
            'total_sales' => $filteredSales->sum('total_amount'),
            'sp_numbers' => $this->sp_numbers,
        ]);
    }

    // Debug method to check SP numbers data
    public function getSpNumbersCountProperty()
    {
        return count($this->sp_numbers);
    }

    
    
    
    private function calculateTotalAmount()
    {
        if ($this->kg_quantity && $this->price_per_kg) {
            $this->total_amount = $this->kg_quantity * $this->price_per_kg;
        }
    }

    
    public function updatedPricePerKg()
    {
        $this->calculateTotalAmount();
    }

    public function updatedKgQuantity()
    {
        $this->calculateTotalAmount();
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
            'customer_name' => $this->customer_name ?? null,
            'customer_address' => $this->customer_address ?? null,
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
        $this->is_fields_disabled = false;

        // Reload SP numbers
        $this->loadSPNumbers();
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
