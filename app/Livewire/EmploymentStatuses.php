<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EmploymentStatus;

class EmploymentStatuses extends Component
{
    use WithPagination;
    // Modal states
    public $showModal = false;
    public $showDeleteConfirmation = false;
    public $isEditing = false;

    // Form fields
    public $name;
    public $value;
    public $description;
    public $is_active = true;
    public $editing_id;
    public $delete_id;
    public $search = '';
    public $deletingEmploymentStatusName = '';
    public $perPage = 10;

    protected $rules = [
        'name' => 'required|unique:employment_statuses,name',
        'value' => 'required|unique:employment_statuses,value',
        'description' => 'nullable',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'name.required' => 'Nama status karyawan wajib diisi.',
        'name.unique' => 'Nama status karyawan sudah terdaftar.',
        'value.required' => 'Nilai status karyawan wajib diisi.',
        'value.unique' => 'Nilai status karyawan sudah terdaftar.',
    ];

    public function mount()
    {
        // Initialize any required data
    }

    public function render()
    {
        $query = EmploymentStatus::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('value', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $employment_statuses = $query->orderBy('name')->paginate($this->perPage);

        return view('livewire.employment-statuses', [
            'employment_statuses' => $employment_statuses,
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
        $employmentStatus = EmploymentStatus::find($id);
        if ($employmentStatus) {
            $this->editing_id = $employmentStatus->id;
            $this->name = $employmentStatus->name;
            $this->value = $employmentStatus->value;
            $this->description = $employmentStatus->description;
            $this->is_active = $employmentStatus->is_active;
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
        $employmentStatus = EmploymentStatus::find($id);
        if ($employmentStatus) {
            $this->delete_id = $id;
            $this->deletingEmploymentStatusName = $employmentStatus->name;
            $this->showDeleteConfirmation = true;
        }
    }

    public function closeDeleteConfirmation()
    {
        $this->showDeleteConfirmation = false;
        $this->delete_id = null;
        $this->deletingEmploymentStatusName = '';
    }

    // CRUD operations
    public function saveEmploymentStatus()
    {
        if ($this->isEditing) {
            $this->validate([
                'name' => 'required|unique:employment_statuses,name,' . $this->editing_id,
                'value' => 'required|unique:employment_statuses,value,' . $this->editing_id,
                'description' => 'nullable',
                'is_active' => 'boolean',
            ]);

            $employmentStatus = EmploymentStatus::find($this->editing_id);
            if ($employmentStatus) {
                $employmentStatus->update([
                    'name' => $this->name,
                    'value' => $this->value,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('message', 'Status karyawan berhasil diperbarui.');
                $this->closeModal();
            }
        } else {
            $this->validate([
                'name' => 'required|unique:employment_statuses,name',
                'value' => 'required|unique:employment_statuses,value',
                'description' => 'nullable',
                'is_active' => 'boolean',
            ]);

            EmploymentStatus::create([
                'name' => $this->name,
                'value' => $this->value,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);

            session()->flash('message', 'Status karyawan berhasil ditambahkan.');
            $this->closeModal();
        }

        }

    public function deleteEmploymentStatus()
    {
        if ($this->delete_id) {
            $employmentStatus = EmploymentStatus::find($this->delete_id);
            if ($employmentStatus) {
                $employmentStatus->delete();
                session()->flash('message', 'Status karyawan berhasil dihapus.');
            }
        }

        $this->closeDeleteConfirmation();
    }

    // Utility methods
    public function resetForm()
    {
        $this->name = '';
        $this->value = '';
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
