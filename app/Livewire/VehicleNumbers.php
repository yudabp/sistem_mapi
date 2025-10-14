<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\VehicleNumber;

class VehicleNumbers extends Component
{
    public $vehicle_numbers = [];
    public $number;
    public $description;
    public $is_active = true;
    public $editing_id;
    public $search = '';

    protected $rules = [
        'number' => 'required|unique:vehicle_numbers,number',
        'description' => 'nullable',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->loadVehicleNumbers();
    }

    public function render()
    {
        return view('livewire.vehicle-numbers');
    }

    public function saveVehicleNumber()
    {
        if ($this->editing_id) {
            $vehicleNumber = VehicleNumber::find($this->editing_id);
            if ($vehicleNumber) {
                $this->validate([
                    'number' => 'required|unique:vehicle_numbers,number,' . $this->editing_id,
                    'description' => 'nullable',
                    'is_active' => 'boolean',
                ]);
                
                $vehicleNumber->update([
                    'number' => $this->number,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('message', 'Nomor kendaraan berhasil diperbarui.');
            }
        } else {
            $validated = $this->validate();
            
            VehicleNumber::create($validated);

            session()->flash('message', 'Nomor kendaraan berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->loadVehicleNumbers();
    }

    public function editVehicleNumber($id)
    {
        $vehicleNumber = VehicleNumber::find($id);
        if ($vehicleNumber) {
            $this->editing_id = $vehicleNumber->id;
            $this->number = $vehicleNumber->number;
            $this->description = $vehicleNumber->description;
            $this->is_active = $vehicleNumber->is_active;
        }
    }

    public function deleteVehicleNumber($id)
    {
        $vehicleNumber = VehicleNumber::find($id);
        if ($vehicleNumber) {
            $vehicleNumber->delete();
            session()->flash('message', 'Nomor kendaraan berhasil dihapus.');
            $this->loadVehicleNumbers();
        }
    }

    public function resetForm()
    {
        $this->number = '';
        $this->description = '';
        $this->is_active = true;
        $this->editing_id = null;
    }

    public function loadVehicleNumbers()
    {
        $query = VehicleNumber::query();

        if ($this->search) {
            $query->where('number', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $this->vehicle_numbers = $query->orderBy('number')->get();
    }
}
