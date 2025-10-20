<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Production as ProductionModel;
use App\Models\VehicleNumber;
use App\Models\Division;
use App\Models\Pks as PksModel;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Production extends Component
{
    use WithFileUploads;

    public $transaction_number;
    public $date;
    public $sp_number;
    public $vehicle_number;
    public $tbs_quantity;
    public $kg_quantity;
    public $division;
    public $pks;
    public $sp_photo;
    
    public $vehicle_numbers = [];
    public $divisions = [];
    public $pks_list = [];
    
    public $search = '';
    public $dateFilter = '';
    public $divisionFilter = '';

    // Modal control
    public $showModal = false;
    public $isEditing = false;
    public $editingId = null;

    // Delete confirmation
    public $showDeleteConfirmation = false;
    public $deletingProductionId = null;
    public $deletingProductionName = '';

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

    protected $queryString = ['search', 'dateFilter', 'divisionFilter', 'metricFilter'];

    protected $rules = [
        'transaction_number' => 'required|unique:production,transaction_number',
        'date' => 'required|date',
        'sp_number' => 'required',
        'vehicle_number' => 'required',
        'tbs_quantity' => 'required|numeric',
        'kg_quantity' => 'required|numeric',
        'division' => 'required',
        'pks' => 'required',
        'sp_photo' => 'nullable|image|max:10240', // Max 10MB
    ];

    public function mount()
    {
        $this->loadOptions();
    }

    public function render()
    {
        $filteredProductions = $this->filterProductions();

        return view('livewire.production', [
            'productions' => $filteredProductions,
            'total_tbs' => $this->getTotalTbs(),
            'total_kg' => $this->getTotalKg(),
        ]);
    }
    
    public function loadOptions()
    {
        $this->vehicle_numbers = VehicleNumber::where('is_active', true)->orderBy('number')->get();
        $this->divisions = Division::where('is_active', true)->orderBy('name')->get();
        $this->pks_list = PksModel::where('is_active', true)->orderBy('name')->get();
    }

    public function saveProduction()
    {
        $validated = $this->validate();
        
        // Handle file upload
        $photoPath = null;
        if ($this->sp_photo) {
            $photoPath = $this->sp_photo->store('sp_photos', 'public');
        }

        ProductionModel::create([
            'transaction_number' => $this->transaction_number,
            'date' => $this->date,
            'sp_number' => $this->sp_number,
            'vehicle_number' => $this->vehicle_number,
            'tbs_quantity' => $this->tbs_quantity,
            'kg_quantity' => $this->kg_quantity,
            'division' => $this->division,
            'pks' => $this->pks,
            'sp_photo_path' => $photoPath,
        ]);

        // Reset form
        $this->resetForm();
        $this->loadOptions();
        
        $this->setPersistentMessage('Production record created successfully.', 'success');
    }

    public function resetForm()
    {
        $this->transaction_number = '';
        $this->date = '';
        $this->sp_number = '';
        $this->vehicle_number = '';
        $this->tbs_quantity = '';
        $this->kg_quantity = '';
        $this->division = '';
        $this->pks = '';
        $this->sp_photo = null;
    }

    public function filterProductions()
    {
        $query = ProductionModel::orderBy('date', 'desc');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('sp_number', 'like', '%' . $this->search . '%')
                  ->orWhere('vehicle_number', 'like', '%' . $this->search . '%')
                  ->orWhere('division', 'like', '%' . $this->search . '%')
                  ->orWhere('transaction_number', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->dateFilter) {
            $query->whereYear('date', '=', substr($this->dateFilter, 0, 4))
                  ->whereMonth('date', '=', substr($this->dateFilter, 5, 2));
        }

        if ($this->divisionFilter) {
            $query->where('division', '=', $this->divisionFilter);
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
                $query->whereDate('date', $now->toDateString());
                break;
            case 'yesterday':
                $yesterday = $now->subDay();
                $query->whereDate('date', $yesterday->toDateString());
                break;
            case 'this_week':
                $query->whereBetween('date', [
                    $now->startOfWeek()->toDateString(),
                    $now->endOfWeek()->toDateString()
                ]);
                break;
            case 'last_week':
                $lastWeekStart = $now->subWeek()->startOfWeek();
                $lastWeekEnd = $now->subWeek()->endOfWeek();
                $query->whereBetween('date', [
                    $lastWeekStart->toDateString(),
                    $lastWeekEnd->toDateString()
                ]);
                break;
            case 'this_month':
                $query->whereYear('date', $now->year)
                      ->whereMonth('date', $now->month);
                break;
            case 'last_month':
                $lastMonth = $now->subMonth();
                $query->whereYear('date', $lastMonth->year)
                      ->whereMonth('date', $lastMonth->month);
                break;
            case 'custom':
                if ($this->startDate && $this->endDate) {
                    $query->whereBetween('date', [$this->startDate, $this->endDate]);
                }
                break;
            case 'all':
            default:
                // No additional filtering for 'all' option
                break;
        }
        
        return $query;
    }

    public function getTotalTbs()
    {
        $query = ProductionModel::query();
        $query = $this->applyMetricFilter($query);
        return $query->sum('tbs_quantity');
    }

    public function getTotalKg()
    {
        $query = ProductionModel::query();
        $query = $this->applyMetricFilter($query);
        return $query->sum('kg_quantity');
    }

    public function deleteProduction($id)
    {
        $production = ProductionModel::find($id);
        if ($production) {
            // Delete the photo if it exists
            if ($production->sp_photo_path) {
                Storage::disk('public')->delete($production->sp_photo_path);
            }
            $production->delete();
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
        $production = ProductionModel::find($id);
        if ($production) {
            $this->editingId = $production->id;
            $this->transaction_number = $production->transaction_number;
            $this->date = $production->date->format('Y-m-d');
            $this->sp_number = $production->sp_number;
            $this->vehicle_number = $production->vehicle_number;
            $this->tbs_quantity = $production->tbs_quantity;
            $this->kg_quantity = $production->kg_quantity;
            $this->division = $production->division;
            $this->pks = $production->pks;
            $this->sp_photo = null; // We don't load the file, just the path
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

    public function confirmDelete($id, $transaction_number)
    {
        $this->deletingProductionId = $id;
        $this->deletingProductionName = $transaction_number;
        $this->showDeleteConfirmation = true;
    }

    public function closeDeleteConfirmation()
    {
        $this->showDeleteConfirmation = false;
        $this->deletingProductionId = null;
        $this->deletingProductionName = '';
    }

    public function deleteProductionConfirmed()
    {
        $production = ProductionModel::find($this->deletingProductionId);
        if ($production) {
            // Delete the photo if it exists
            if ($production->sp_photo_path) {
                Storage::disk('public')->delete($production->sp_photo_path);
            }
            $production->delete();
            $this->setPersistentMessage('Production record deleted successfully.', 'success');
        }
        
        $this->closeDeleteConfirmation();
    }

    public function saveProductionModal()
    {
        if ($this->isEditing) {
            $this->updateProduction();
        } else {
            $this->saveProduction();
        }
        
        $this->closeCreateModal();
    }

    public function updateProduction()
    {
        $validated = $this->validate();
        
        $production = ProductionModel::find($this->editingId);
        if ($production) {
            // Handle file upload
            $photoPath = $production->sp_photo_path; // Keep existing path if no new file
            if ($this->sp_photo) {
                // Delete old photo if exists
                if ($production->sp_photo_path) {
                    Storage::disk('public')->delete($production->sp_photo_path);
                }
                $photoPath = $this->sp_photo->store('sp_photos', 'public');
            }

            $production->update([
                'transaction_number' => $this->transaction_number,
                'date' => $this->date,
                'sp_number' => $this->sp_number,
                'vehicle_number' => $this->vehicle_number,
                'tbs_quantity' => $this->tbs_quantity,
                'kg_quantity' => $this->kg_quantity,
                'division' => $this->division,
                'pks' => $this->pks,
                'sp_photo_path' => $photoPath,
            ]);

            $this->setPersistentMessage('Production record updated successfully.', 'success');
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