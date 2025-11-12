<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Position;

class Positions extends Component
{
    use WithPagination;
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
    public $deletingPositionName = '';
    public $perPage = 10;

    protected $rules = [
        'name' => 'required|unique:positions,name',
        'description' => 'nullable',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'name.required' => 'Nama jabatan wajib diisi.',
        'name.unique' => 'Nama jabatan sudah terdaftar.',
    ];

    public function mount()
    {
        // Initialize any required data
    }

    public function render()
    {
        $query = Position::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $positions = $query->orderBy('name')->paginate($this->perPage);

        return view('livewire.positions', [
            'positions' => $positions,
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
        $position = Position::find($id);
        if ($position) {
            $this->editing_id = $position->id;
            $this->name = $position->name;
            $this->description = $position->description;
            $this->is_active = $position->is_active;
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
        $position = Position::find($id);
        if ($position) {
            $this->delete_id = $id;
            $this->deletingPositionName = $position->name;
            $this->showDeleteConfirmation = true;
        }
    }

    public function closeDeleteConfirmation()
    {
        $this->showDeleteConfirmation = false;
        $this->delete_id = null;
        $this->deletingPositionName = '';
    }

    // CRUD operations
    public function savePosition()
    {
        if ($this->isEditing) {
            $this->validate([
                'name' => 'required|unique:positions,name,' . $this->editing_id,
                'description' => 'nullable',
                'is_active' => 'boolean',
            ]);

            $position = Position::find($this->editing_id);
            if ($position) {
                $position->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('message', 'Jabatan berhasil diperbarui.');
                $this->closeModal();
            }
        } else {
            $this->validate([
                'name' => 'required|unique:positions,name',
                'description' => 'nullable',
                'is_active' => 'boolean',
            ]);

            Position::create([
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);

            session()->flash('message', 'Jabatan berhasil ditambahkan.');
            $this->closeModal();
        }

        }

    public function deletePosition()
    {
        if ($this->delete_id) {
            $position = Position::find($this->delete_id);
            if ($position) {
                $position->delete();
                session()->flash('message', 'Jabatan berhasil dihapus.');
            }
        }

        $this->closeDeleteConfirmation();
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
