<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Department;

class Departments extends Component
{
    public $departments = [];
    public $name;
    public $description;
    public $is_active = true;
    public $editing_id;
    public $search = '';

    protected $rules = [
        'name' => 'required|unique:departments,name',
        'description' => 'nullable',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->loadDepartments();
    }

    public function render()
    {
        return view('livewire.departments');
    }

    public function saveDepartment()
    {
        if ($this->editing_id) {
            $department = Department::find($this->editing_id);
            if ($department) {
                $this->validate([
                    'name' => 'required|unique:departments,name,' . $this->editing_id,
                    'description' => 'nullable',
                    'is_active' => 'boolean',
                ]);
                
                $department->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('message', 'Bagian berhasil diperbarui.');
            }
        } else {
            $validated = $this->validate();
            
            Department::create($validated);

            session()->flash('message', 'Bagian berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->loadDepartments();
    }

    public function editDepartment($id)
    {
        $department = Department::find($id);
        if ($department) {
            $this->editing_id = $department->id;
            $this->name = $department->name;
            $this->description = $department->description;
            $this->is_active = $department->is_active;
        }
    }

    public function deleteDepartment($id)
    {
        $department = Department::find($id);
        if ($department) {
            $department->delete();
            session()->flash('message', 'Bagian berhasil dihapus.');
            $this->loadDepartments();
        }
    }

    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->is_active = true;
        $this->editing_id = null;
    }

    public function loadDepartments()
    {
        $query = Department::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $this->departments = $query->orderBy('name')->get();
    }
}
