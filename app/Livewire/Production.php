<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Production as ProductionModel;
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
    
    public $productions = [];
    public $search = '';
    public $dateFilter = '';
    public $divisionFilter = '';

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
        $this->loadProductions();
    }

    public function render()
    {
        return view('livewire.production', [
            'productions' => $this->filterProductions(),
            'total_tbs' => $this->productions->sum('tbs_quantity'),
            'total_kg' => $this->productions->sum('kg_quantity'),
        ]);
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
        $this->loadProductions();
        
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

    public function loadProductions()
    {
        $this->productions = ProductionModel::orderBy('date', 'desc')->get();
    }

    public function filterProductions()
    {
        $productions = $this->productions;

        if ($this->search) {
            $productions = $productions->filter(function ($item) {
                return stripos($item->sp_number, $this->search) !== false ||
                       stripos($item->vehicle_number, $this->search) !== false ||
                       stripos($item->division, $this->search) !== false;
            });
        }

        if ($this->dateFilter) {
            $productions = $productions->filter(function ($item) {
                return $item->date->format('Y-m') === $this->dateFilter;
            });
        }

        if ($this->divisionFilter) {
            $productions = $productions->filter(function ($item) {
                return $item->division === $this->divisionFilter;
            });
        }

        return $productions;
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
            $this->loadProductions();
        }
    }
}
