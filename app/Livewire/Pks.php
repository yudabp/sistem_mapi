<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pks as PksModel;

class Pks extends Component
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
    public $deletingPksName = '';

    // Data
    public $pks_list = [];

    protected $rules = [
        'name' => 'required|unique:pks,name',
        'description' => 'nullable',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'name.required' => 'Nama PKS wajib diisi.',
        'name.unique' => 'Nama PKS sudah terdaftar.',
    ];

    public function updatedSearch()
    {
        $this->loadPks();
    }

    public function mount()
    {
        $this->loadPks();
    }

    public function render()
    {
        return view('livewire.pks');
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
        $pks = PksModel::find($id);
        if ($pks) {
            $this->editing_id = $pks->id;
            $this->name = $pks->name;
            $this->description = $pks->description;
            $this->is_active = $pks->is_active;
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
        $pks = PksModel::find($id);
        if ($pks) {
            $this->delete_id = $id;
            $this->deletingPksName = $pks->name;
            $this->showDeleteConfirmation = true;
        }
    }

    public function closeDeleteConfirmation()
    {
        $this->showDeleteConfirmation = false;
        $this->delete_id = null;
        $this->deletingPksName = '';
    }

    // CRUD operations
    public function savePks()
    {
        if ($this->isEditing) {
            $this->validate([
                'name' => 'required|unique:pks,name,' . $this->editing_id,
                'description' => 'nullable',
                'is_active' => 'boolean',
            ]);

            $pks = PksModel::find($this->editing_id);
            if ($pks) {
                $pks->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('message', 'PKS berhasil diperbarui.');
                $this->closeModal();
            }
        } else {
            $this->validate([
                'name' => 'required|unique:pks,name',
                'description' => 'nullable',
                'is_active' => 'boolean',
            ]);

            PksModel::create([
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);

            session()->flash('message', 'PKS berhasil ditambahkan.');
            $this->closeModal();
        }

        $this->loadPks();
    }

    public function deletePks()
    {
        if ($this->delete_id) {
            $pks = PksModel::find($this->delete_id);
            if ($pks) {
                $pks->delete();
                session()->flash('message', 'PKS berhasil dihapus.');
            }
        }

        $this->closeDeleteConfirmation();
        $this->loadPks();
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
        $this->loadPks();
    }

    public function loadPks()
    {
        $query = PksModel::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $this->pks_list = $query->orderBy('name')->get();
    }
}
