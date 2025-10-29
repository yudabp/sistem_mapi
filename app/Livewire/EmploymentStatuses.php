<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EmploymentStatus;

class EmploymentStatuses extends Component
{
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

    // Data
    public $employment_statuses = [];

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

    public function updatedSearch()
    {
        $this->loadEmploymentStatuses();
    }

    public function mount()
    {
        $this->loadEmploymentStatuses();
    }

    public function render()
    {
        return view('livewire.employment-statuses');
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

        $this->loadEmploymentStatuses();
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
        $this->loadEmploymentStatuses();
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
        $this->loadEmploymentStatuses();
    }

    public function loadEmploymentStatuses()
    {
        $query = EmploymentStatus::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('value', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $this->employment_statuses = $query->orderBy('name')->get();
    }
}
