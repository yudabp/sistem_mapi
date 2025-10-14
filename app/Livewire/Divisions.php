<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Division;

class Divisions extends Component
{
    public $divisions = [];
    public $name;
    public $description;
    public $is_active = true;
    public $editing_id;
    public $search = '';

    protected $rules = [
        'name' => 'required|unique:divisions,name',
        'description' => 'nullable',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->loadDivisions();
    }

    public function render()
    {
        return view('livewire.divisions');
    }

    public function saveDivision()
    {
        if ($this->editing_id) {
            $division = Division::find($this->editing_id);
            if ($division) {
                $this->validate([
                    'name' => 'required|unique:divisions,name,' . $this->editing_id,
                    'description' => 'nullable',
                    'is_active' => 'boolean',
                ]);
                
                $division->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('message', 'Afdeling berhasil diperbarui.');
            }
        } else {
            $validated = $this->validate();
            
            Division::create($validated);

            session()->flash('message', 'Afdeling berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->loadDivisions();
    }

    public function editDivision($id)
    {
        $division = Division::find($id);
        if ($division) {
            $this->editing_id = $division->id;
            $this->name = $division->name;
            $this->description = $division->description;
            $this->is_active = $division->is_active;
        }
    }

    public function deleteDivision($id)
    {
        $division = Division::find($id);
        if ($division) {
            $division->delete();
            session()->flash('message', 'Afdeling berhasil dihapus.');
            $this->loadDivisions();
        }
    }

    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->is_active = true;
        $this->editing_id = null;
    }

    public function loadDivisions()
    {
        $query = Division::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $this->divisions = $query->orderBy('name')->get();
    }
}
