<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Employee as EmployeeModel;
use App\Models\Department;
use App\Models\Position;
use App\Models\FamilyComposition;
use App\Models\EmploymentStatus;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Employees extends Component
{
    public $ndp; // Employee ID
    public $name;
    public $department;
    public $position;
    public $grade;
    public $family_composition;
    public $monthly_salary;
    public $status;
    public $hire_date;
    public $address;
    public $phone;
    public $email;
    
    public $departments = [];
    public $positions = [];
    public $family_compositions = [];
    public $employment_statuses = [];
    
    public $search = '';
    public $departmentFilter = '';

    // Modal control
    public $showModal = false;
    public $isEditing = false;
    public $editingId = null;

    // Delete confirmation
    public $showDeleteConfirmation = false;
    public $deletingEmployeeId = null;
    public $deletingEmployeeName = '';

    // Persistent message
    public $persistentMessage = '';
    public $messageType = 'success'; // success, error, warning, info

    protected $queryString = ['search', 'departmentFilter'];

    protected $rules = [
        'ndp' => 'required|unique:employees,ndp',
        'name' => 'required',
        'department' => 'required',
        'position' => 'required',
        'monthly_salary' => 'required|numeric',
        'hire_date' => 'required|date',
        'status' => 'required',
    ];

    public function mount()
    {
        $this->loadOptions();
    }

    public function render()
    {
        $filteredEmployees = $this->filterEmployees();

        return view('livewire.employees', [
            'employees' => $filteredEmployees,
            'total_employees' => $filteredEmployees->count(),
            'total_salary' => $filteredEmployees->sum('monthly_salary'),
        ]);
    }
    
    public function loadOptions()
    {
        $this->departments = Department::where('is_active', true)->orderBy('name')->get();
        $this->positions = Position::where('is_active', true)->orderBy('name')->get();
        $this->family_compositions = FamilyComposition::where('is_active', true)->orderBy('number')->get();
        $this->employment_statuses = EmploymentStatus::where('is_active', true)->orderBy('name')->get();
    }

    public function saveEmployee()
    {
        $validated = $this->validate();
        
        EmployeeModel::create([
            'ndp' => $this->ndp,
            'name' => $this->name,
            'department' => $this->department,
            'position' => $this->position,
            'grade' => $this->grade,
            'family_composition' => $this->family_composition,
            'monthly_salary' => $this->monthly_salary,
            'status' => $this->status,
            'hire_date' => $this->hire_date,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
        ]);

        // Reset form
        $this->resetForm();
        $this->loadOptions();
        
        $this->setPersistentMessage('Employee record created successfully.', 'success');
    }

    public function resetForm()
    {
        $this->ndp = '';
        $this->name = '';
        $this->department = '';
        $this->position = '';
        $this->grade = '';
        $this->family_composition = 0;
        $this->monthly_salary = '';
        $this->status = 'active';
        $this->hire_date = '';
        $this->address = '';
        $this->phone = '';
        $this->email = '';
    }

    public function filterEmployees()
    {
        $query = EmployeeModel::orderBy('name', 'asc');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('ndp', 'like', '%' . $this->search . '%')
                  ->orWhere('name', 'like', '%' . $this->search . '%')
                  ->orWhere('department', 'like', '%' . $this->search . '%')
                  ->orWhere('position', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->departmentFilter) {
            $query->where('department', '=', $this->departmentFilter);
        }

        return $query->get();
    }

    public function deleteEmployee($id)
    {
        $employee = EmployeeModel::find($id);
        if ($employee) {
            $employee->delete();
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $employee = EmployeeModel::find($id);
        if ($employee) {
            $this->editingId = $employee->id;
            $this->ndp = $employee->ndp;
            $this->name = $employee->name;
            $this->department = $employee->department;
            $this->position = $employee->position;
            $this->grade = $employee->grade;
            $this->family_composition = $employee->family_composition;
            $this->monthly_salary = $employee->monthly_salary;
            $this->status = $employee->status;
            $this->hire_date = $employee->hire_date->format('Y-m-d');
            $this->address = $employee->address;
            $this->phone = $employee->phone;
            $this->email = $employee->email;
            $this->isEditing = true;
            $this->showModal = true;
        }
    }

    public function closeCreateModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->isEditing = false;
        $this->editingId = null;
    }

    public function confirmDelete($id, $name)
    {
        $this->deletingEmployeeId = $id;
        $this->deletingEmployeeName = $name;
        $this->showDeleteConfirmation = true;
    }

    public function closeDeleteConfirmation()
    {
        $this->showDeleteConfirmation = false;
        $this->deletingEmployeeId = null;
        $this->deletingEmployeeName = '';
    }

    public function deleteEmployeeConfirmed()
    {
        $employee = EmployeeModel::find($this->deletingEmployeeId);
        if ($employee) {
            $employee->delete();
            $this->setPersistentMessage('Employee record deleted successfully.', 'success');
        }
        
        $this->closeDeleteConfirmation();
    }

    public function saveEmployeeModal()
    {
        if ($this->isEditing) {
            $this->updateEmployee();
        } else {
            $this->saveEmployee();
        }
        
        $this->closeCreateModal();
    }

    public function updateEmployee()
    {
        $validated = $this->validate();
        
        $employee = EmployeeModel::find($this->editingId);
        if ($employee) {
            $employee->update([
                'ndp' => $this->ndp,
                'name' => $this->name,
                'department' => $this->department,
                'position' => $this->position,
                'grade' => $this->grade,
                'family_composition' => $this->family_composition,
                'monthly_salary' => $this->monthly_salary,
                'status' => $this->status,
                'hire_date' => $this->hire_date,
                'address' => $this->address,
                'phone' => $this->phone,
                'email' => $this->email,
            ]);

            $this->setPersistentMessage('Employee record updated successfully.', 'success');
        }
    }

    public function setPersistentMessage($message, $type = 'success')
    {
        $this->persistentMessage = $message;
        $this->messageType = $type;
    }

    public function clearPersistentMessage()
    {
        $this->persistentMessage = '';
    }
}
