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
        
        session()->flash('message', 'Employee record created successfully.');
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
}
