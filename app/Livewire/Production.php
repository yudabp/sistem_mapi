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

    protected $queryString = ['search', 'dateFilter', 'divisionFilter'];

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
            'total_tbs' => $filteredProductions->sum('tbs_quantity'),
            'total_kg' => $filteredProductions->sum('kg_quantity'),
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
        
        session()->flash('message', 'Production record created successfully.');
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

        return $query->get();
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
}