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

    public $sp_number; // Keep for backward compatibility
    public $production_id;
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

    // Modal control
    public $showModal = false;
    public $isEditing = false;
    public $editingId = null;

    // Delete confirmation
    public $showDeleteConfirmation = false;
    public $deletingSaleId = null;
    public $deletingSaleName = '';

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

    protected $queryString = ['search', 'dateFilter', 'metricFilter'];

    protected $rules = [
        'production_id' => 'required|exists:production,id',
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
        $productionData = ProductionModel::select('id', 'sp_number', 'tbs_quantity', 'kg_quantity')
                                        ->orderBy('sp_number')
                                        ->get();

        return view('livewire.sales', [
            'sales' => $filteredSales,
            'total_kg' => $this->getTotalKg(),
            'total_sales' => $this->getTotalSales(),
            'productionData' => $productionData,
        ]);
    }

    public function updatedPricePerKg()
    {
        if ($this->kg_quantity && $this->price_per_kg) {
            $this->total_amount = $this->kg_quantity * $this->price_per_kg;
        } else {
            $this->total_amount = '';
        }
    }

    public function updatedKgQuantity()
    {
        if ($this->kg_quantity && $this->price_per_kg) {
            $this->total_amount = $this->kg_quantity * $this->price_per_kg;
        }
    }

    public function updatedSpNumber()
    {
        // Keep for backward compatibility
        if ($this->sp_number) {
            $production = ProductionModel::where('sp_number', $this->sp_number)->first();
            if ($production) {
                $this->production_id = $production->id;
                $this->tbs_quantity = $production->tbs_quantity;
                $this->kg_quantity = $production->kg_quantity;
                
                // Auto-calculate total amount if price per kg is already set
                if ($this->kg_quantity && $this->price_per_kg) {
                    $this->total_amount = $this->kg_quantity * $this->price_per_kg;
                }
            } else {
                // Reset if production not found
                $this->production_id = '';
                $this->tbs_quantity = '';
                $this->kg_quantity = '';
                $this->total_amount = '';
            }
        } else {
            $this->production_id = '';
            $this->tbs_quantity = '';
            $this->kg_quantity = '';
            $this->total_amount = '';
        }
    }

    public function updatedProductionId()
    {
        if ($this->production_id) {
            $production = ProductionModel::find($this->production_id);
            if ($production) {
                $this->sp_number = $production->sp_number;
                $this->tbs_quantity = $production->tbs_quantity;
                $this->kg_quantity = $production->kg_quantity;
                
                // Auto-calculate total amount if price per kg is already set
                if ($this->kg_quantity && $this->price_per_kg) {
                    $this->total_amount = $this->kg_quantity * $this->price_per_kg;
                }
            } else {
                // Reset if production not found
                $this->sp_number = '';
                $this->tbs_quantity = '';
                $this->kg_quantity = '';
                $this->total_amount = '';
            }
        } else {
            $this->sp_number = '';
            $this->tbs_quantity = '';
            $this->kg_quantity = '';
            $this->total_amount = '';
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
            'sp_number' => $this->sp_number, // Keep for backward compatibility
            'production_id' => $this->production_id,
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
        
        $this->setPersistentMessage('Sales record created successfully.', 'success');
    }

    public function resetForm()
    {
        $this->sp_number = ''; // Keep for backward compatibility
        $this->production_id = '';
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

        // Apply metric filter
        $query = $this->applyMetricFilter($query);

        return $query->get();
    }

    public function applyMetricFilter($query)
    {
        $now = now();
        
        switch ($this->metricFilter) {
            case 'today':
                $query->whereDate('sale_date', $now->toDateString());
                break;
            case 'yesterday':
                $yesterday = $now->subDay();
                $query->whereDate('sale_date', $yesterday->toDateString());
                break;
            case 'this_week':
                $query->whereBetween('sale_date', [
                    $now->startOfWeek()->toDateString(),
                    $now->endOfWeek()->toDateString()
                ]);
                break;
            case 'last_week':
                $lastWeekStart = $now->subWeek()->startOfWeek();
                $lastWeekEnd = $now->subWeek()->endOfWeek();
                $query->whereBetween('sale_date', [
                    $lastWeekStart->toDateString(),
                    $lastWeekEnd->toDateString()
                ]);
                break;
            case 'this_month':
                $query->whereYear('sale_date', $now->year)
                      ->whereMonth('sale_date', $now->month);
                break;
            case 'last_month':
                $lastMonth = $now->subMonth();
                $query->whereYear('sale_date', $lastMonth->year)
                      ->whereMonth('sale_date', $lastMonth->month);
                break;
            case 'custom':
                if ($this->startDate && $this->endDate) {
                    $query->whereBetween('sale_date', [$this->startDate, $this->endDate]);
                }
                break;
            case 'all':
            default:
                // No additional filtering for 'all' option
                break;
        }
        
        return $query;
    }

    public function getTotalKg()
    {
        $query = SaleModel::query();
        $query = $this->applyMetricFilter($query);
        return $query->sum('kg_quantity');
    }

    public function getTotalSales()
    {
        $query = SaleModel::query();
        $query = $this->applyMetricFilter($query);
        return $query->sum('total_amount');
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

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $sale = SaleModel::find($id);
        if ($sale) {
            $this->editingId = $sale->id;
            $this->sp_number = $sale->sp_number;
            $this->tbs_quantity = $sale->tbs_quantity;
            $this->kg_quantity = $sale->kg_quantity;
            $this->price_per_kg = $sale->price_per_kg;
            $this->total_amount = $sale->total_amount;
            $this->sale_date = $sale->sale_date->format('Y-m-d');
            $this->customer_name = $sale->customer_name;
            $this->customer_address = $sale->customer_address;
            $this->sales_proof = null; // We don't load the file, just the path
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

    public function confirmDelete($id, $sp_number)
    {
        $this->deletingSaleId = $id;
        $this->deletingSaleName = $sp_number;
        $this->showDeleteConfirmation = true;
    }

    public function closeDeleteConfirmation()
    {
        $this->showDeleteConfirmation = false;
        $this->deletingSaleId = null;
        $this->deletingSaleName = '';
    }

    public function deleteSalesConfirmed()
    {
        $sale = SaleModel::find($this->deletingSaleId);
        if ($sale) {
            // Delete the proof if it exists
            if ($sale->sales_proof_path) {
                Storage::disk('public')->delete($sale->sales_proof_path);
            }
            $sale->delete();
            $this->setPersistentMessage('Sales record deleted successfully.', 'success');
        }
        
        $this->closeDeleteConfirmation();
    }

    public function saveSalesModal()
    {
        if ($this->isEditing) {
            $this->updateSale();
        } else {
            $this->saveSales();
        }
        
        $this->closeCreateModal();
    }

    public function updateSale()
    {
        $validated = $this->validate();
        
        $sale = SaleModel::find($this->editingId);
        if ($sale) {
            // Handle file upload
            $proofPath = $sale->sales_proof_path; // Keep existing path if no new file
            if ($this->sales_proof) {
                // Delete old proof if exists
                if ($sale->sales_proof_path) {
                    Storage::disk('public')->delete($sale->sales_proof_path);
                }
                $proofPath = $this->sales_proof->store('sales_proofs', 'public');
            }

            $sale->update([
                'sp_number' => $this->sp_number,
                'tbs_quantity' => $this->tbs_quantity,
                'kg_quantity' => $this->kg_quantity,
                'price_per_kg' => $this->price_per_kg,
                'total_amount' => $this->total_amount,
                'sale_date' => $this->sale_date,
                'customer_name' => $this->customer_name,
                'customer_address' => $this->customer_address,
                'sales_proof_path' => $proofPath,
            ]);

            $this->setPersistentMessage('Sales record updated successfully.', 'success');
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
