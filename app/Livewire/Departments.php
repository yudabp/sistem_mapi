<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Department;

class Departments extends Component
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
    public $deletingDepartmentName = '';
    public $perPage = 10;

    protected $rules = [
        'name' => 'required|unique:departments,name',
        'description' => 'nullable',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'name.required' => 'Nama bagian wajib diisi.',
        'name.unique' => 'Nama bagian sudah terdaftar.',
    ];

    public function mount()
    {
        // Initialize any required data
    }

    public function render()
    {
        $query = Department::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $departments = $query->orderBy('name')->paginate($this->perPage);

        return view('livewire.departments', [
            'departments' => $departments,
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
        $department = Department::find($id);
        if ($department) {
            $this->editing_id = $department->id;
            $this->name = $department->name;
            $this->description = $department->description;
            $this->is_active = $department->is_active;
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
        $department = Department::find($id);
        if ($department) {
            $this->delete_id = $id;
            $this->deletingDepartmentName = $department->name;
            $this->showDeleteConfirmation = true;
        }
    }

    public function closeDeleteConfirmation()
    {
        $this->showDeleteConfirmation = false;
        $this->delete_id = null;
        $this->deletingDepartmentName = '';
    }

    // CRUD operations
    public function saveDepartment()
    {
        if ($this->isEditing) {
            $this->validate([
                'name' => 'required|unique:departments,name,' . $this->editing_id,
                'description' => 'nullable',
                'is_active' => 'boolean',
            ]);

            $department = Department::find($this->editing_id);
            if ($department) {
                $department->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('message', 'Bagian berhasil diperbarui.');
                $this->closeModal();
            }
        } else {
            $this->validate([
                'name' => 'required|unique:departments,name',
                'description' => 'nullable',
                'is_active' => 'boolean',
            ]);

            Department::create([
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);

            session()->flash('message', 'Bagian berhasil ditambahkan.');
            $this->closeModal();
        }

    }

    public function deleteDepartment()
    {
        if ($this->delete_id) {
            $department = Department::find($this->delete_id);
            if ($department) {
                $department->delete();
                session()->flash('message', 'Bagian berhasil dihapus.');
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
