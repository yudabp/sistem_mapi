<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Division;

class Divisions extends Component
{
    // Modal states
    public $showModal = false;
    public $showDeleteConfirmation = false;
    public $isEditing = false;

    // Form fields
    public $name;
    public $description;
    public $is_active = true;
    public $editing_id;
    public $delete_id;
    public $search = '';
    public $deletingDivisionName = '';

    // Data
    public $divisions;

    protected $rules = [
        'name' => 'required|unique:divisions,name',
        'description' => 'nullable',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'name.required' => 'Nama afdeling wajib diisi.',
        'name.unique' => 'Nama afdeling sudah terdaftar.',
    ];

    public function mount()
    {
        $this->loadDivisions();
    }

    public function render()
    {
        return view('livewire.divisions');
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
        $division = Division::find($id);
        if ($division) {
            $this->editing_id = $division->id;
            $this->name = $division->name;
            $this->description = $division->description;
            $this->is_active = $division->is_active;
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
        $division = Division::find($id);
        if ($division) {
            $this->delete_id = $id;
            $this->deletingDivisionName = $division->name;
            $this->showDeleteConfirmation = true;
        }
    }

    public function closeDeleteConfirmation()
    {
        $this->showDeleteConfirmation = false;
        $this->delete_id = null;
        $this->deletingDivisionName = '';
    }

    // CRUD operations
    public function saveDivision()
    {
        if ($this->isEditing) {
            $this->validate([
                'name' => 'required|unique:divisions,name,' . $this->editing_id,
                'description' => 'nullable',
                'is_active' => 'boolean',
            ]);

            $division = Division::find($this->editing_id);
            if ($division) {
                $division->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('message', 'Afdeling berhasil diperbarui.');
                $this->closeModal();
            }
        } else {
            $this->validate([
                'name' => 'required|unique:divisions,name',
                'description' => 'nullable',
                'is_active' => 'boolean',
            ]);

            Division::create([
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);

            session()->flash('message', 'Afdeling berhasil ditambahkan.');
            $this->closeModal();
        }

        $this->loadDivisions();
    }

    public function deleteDivision()
    {
        if ($this->delete_id) {
            $division = Division::find($this->delete_id);
            if ($division) {
                $division->delete();
                session()->flash('message', 'Afdeling berhasil dihapus.');
            }
        }

        $this->closeDeleteConfirmation();
        $this->loadDivisions();
    }

    // Utility methods
    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->is_active = true;
        $this->editing_id = null;
        $this->isEditing = false;
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->loadDivisions();
    }

    public function loadDivisions()
    {
        $query = Division::query();

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
        }

        $this->divisions = $query->orderBy('name')->get();
    }
}