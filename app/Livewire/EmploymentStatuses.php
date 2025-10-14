<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EmploymentStatus;

class EmploymentStatuses extends Component
{
    public $employment_statuses = [];
    public $name;
    public $value;
    public $description;
    public $is_active = true;
    public $editing_id;
    public $search = '';

    protected $rules = [
        'name' => 'required|unique:employment_statuses,name',
        'value' => 'required|unique:employment_statuses,value',
        'description' => 'nullable',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->loadEmploymentStatuses();
    }

    public function render()
    {
        return view('livewire.employment-statuses');
    }

    public function saveEmploymentStatus()
    {
        if ($this->editing_id) {
            $employmentStatus = EmploymentStatus::find($this->editing_id);
            if ($employmentStatus) {
                $this->validate([
                    'name' => 'required|unique:employment_statuses,name,' . $this->editing_id,
                    'value' => 'required|unique:employment_statuses,value,' . $this->editing_id,
                    'description' => 'nullable',
                    'is_active' => 'boolean',
                ]);
                
                $employmentStatus->update([
                    'name' => $this->name,
                    'value' => $this->value,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('message', 'Status karyawan berhasil diperbarui.');
            }
        } else {
            $validated = $this->validate();
            
            EmploymentStatus::create($validated);

            session()->flash('message', 'Status karyawan berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->loadEmploymentStatuses();
    }

    public function editEmploymentStatus($id)
    {
        $employmentStatus = EmploymentStatus::find($id);
        if ($employmentStatus) {
            $this->editing_id = $employmentStatus->id;
            $this->name = $employmentStatus->name;
            $this->value = $employmentStatus->value;
            $this->description = $employmentStatus->description;
            $this->is_active = $employmentStatus->is_active;
        }
    }

    public function deleteEmploymentStatus($id)
    {
        $employmentStatus = EmploymentStatus::find($id);
        if ($employmentStatus) {
            $employmentStatus->delete();
            session()->flash('message', 'Status karyawan berhasil dihapus.');
            $this->loadEmploymentStatuses();
        }
    }

    public function resetForm()
    {
        $this->name = '';
        $this->value = '';
        $this->description = '';
        $this->is_active = true;
        $this->editing_id = null;
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
