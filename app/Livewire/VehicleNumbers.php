<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\VehicleNumber;

class VehicleNumbers extends Component
{
    use WithPagination;
    // Modal states
    public $showModal = false;
    public $showDeleteConfirmation = false;
    public $isEditing = false;

    // Form fields
    public $number;
    public $description;
    public $is_active = true;
    public $editing_id;
    public $delete_id;
    public $search = '';
    public $deletingVehicleNumber = '';
    public $perPage = 10;

    
    protected $rules = [
        'number' => 'required|unique:vehicle_numbers,number',
        'description' => 'nullable',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'number.required' => 'Nomor polisi wajib diisi.',
        'number.unique' => 'Nomor polisi sudah terdaftar.',
    ];

    public function mount()
    {
        // Initialize any required data
    }

    public function render()
    {
        $query = VehicleNumber::query();

        if ($this->search) {
            $query->where('number', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $vehicle_numbers = $query->orderBy('number')->paginate($this->perPage);

        return view('livewire.vehicle-numbers', [
            'vehicle_numbers' => $vehicle_numbers,
        ]);
    }

    // Modal methods
    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $vehicleNumber = VehicleNumber::find($id);
        if ($vehicleNumber) {
            $this->editing_id = $vehicleNumber->id;
            $this->number = $vehicleNumber->number;
            $this->description = $vehicleNumber->description;
            $this->is_active = $vehicleNumber->is_active;
            $this->isEditing = true;
            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete($id)
    {
        $vehicleNumber = VehicleNumber::find($id);
        if ($vehicleNumber) {
            $this->delete_id = $id;
            $this->deletingVehicleNumber = $vehicleNumber->number;
            $this->showDeleteConfirmation = true;
        }
    }

    public function closeDeleteConfirmation()
    {
        $this->showDeleteConfirmation = false;
        $this->delete_id = null;
        $this->deletingVehicleNumber = '';
    }

    // CRUD operations
    public function saveVehicle()
    {
        if ($this->isEditing) {
            $this->validate([
                'number' => 'required|unique:vehicle_numbers,number,' . $this->editing_id,
                'description' => 'nullable',
                'is_active' => 'boolean',
            ]);

            $vehicleNumber = VehicleNumber::find($this->editing_id);
            if ($vehicleNumber) {
                $vehicleNumber->update([
                    'number' => $this->number,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('message', 'No Polisi berhasil diperbarui.');
                $this->closeModal();
            }
        } else {
            $this->validate([
                'number' => 'required|unique:vehicle_numbers,number',
                'description' => 'nullable',
                'is_active' => 'boolean',
            ]);

            VehicleNumber::create([
                'number' => $this->number,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);

            session()->flash('message', 'No Polisi berhasil ditambahkan.');
            $this->closeModal();
        }
    }

    public function deleteVehicle()
    {
        if ($this->delete_id) {
            $vehicleNumber = VehicleNumber::find($this->delete_id);
            if ($vehicleNumber) {
                $vehicleNumber->delete();
                session()->flash('message', 'No Polisi berhasil dihapus.');
            }
        }

        $this->closeDeleteConfirmation();
        // Transactions are loaded in render() method
    }

    // Utility methods
    public function resetForm()
    {
        $this->number = '';
        $this->description = '';
        $this->is_active = true;
        $this->editing_id = null;
        $this->isEditing = false;
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }
}