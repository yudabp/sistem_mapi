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
use App\Imports\EmployeesImport;
use App\Exports\EmployeesExportWithHeaders;
use App\Exports\EmployeesPdfExporter;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Livewire\Concerns\WithRoleCheck;

class Employees extends Component
{
    use WithFileUploads;
    use WithRoleCheck;
    public $ndp; // Employee ID
    public $name;
    public $department; // Keep for backward compatibility
    public $department_id;
    public $position; // Keep for backward compatibility
    public $position_id;
    public $grade;
    public $family_composition; // Keep for backward compatibility
    public $family_composition_id;
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

    public $importFile = null;
    public $exportStartDate = null;
    public $exportEndDate = null;
    public $showImportModal = false;

    protected $rules = [
        'ndp' => 'required|unique:employees,ndp',
        'name' => 'required',
        'department_id' => 'required|exists:departments,id',
        'position_id' => 'required|exists:positions,id',
        'monthly_salary' => 'required|numeric',
        'hire_date' => 'required|date_format:d-m-Y',
        'status' => 'required',
        'importFile' => 'required|file|mimes:xlsx,xls,csv',
    ];

    public function mount()
    {
        $this->mountWithRoleCheck();
        $this->loadOptions();
        // Set default export dates: start date 1 month ago, end date today in DD-MM-YYYY format
        if (!$this->exportStartDate) {
            $this->exportStartDate = now()->subMonth()->format('d-m-Y');
        }
        if (!$this->exportEndDate) {
            $this->exportEndDate = now()->format('d-m-Y');
        }
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
        $this->authorizeEdit();
        $validated = $this->validate();
        
        // Convert date from DD-MM-YYYY to YYYY-MM-DD format for database storage
        $hireDateForDb = \DateTime::createFromFormat('d-m-Y', $this->hire_date)->format('Y-m-d');
        
        EmployeeModel::create([
            'ndp' => $this->ndp,
            'name' => $this->name,
            'department' => $this->department, // Keep for backward compatibility
            'department_id' => $this->department_id,
            'position' => $this->position, // Keep for backward compatibility
            'position_id' => $this->position_id,
            'grade' => $this->grade,
            'family_composition' => $this->family_composition, // Keep for backward compatibility
            'family_composition_id' => $this->family_composition_id,
            'monthly_salary' => $this->monthly_salary,
            'status' => $this->status,
            'hire_date' => $hireDateForDb,
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
        $this->department = ''; // Keep for backward compatibility
        $this->department_id = '';
        $this->position = ''; // Keep for backward compatibility
        $this->position_id = '';
        $this->grade = '';
        $this->family_composition = 0; // Keep for backward compatibility
        $this->family_composition_id = '';
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
            $this->hire_date = $employee->hire_date->format('d-m-Y'); // Format for DD-MM-YYYY display
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
        $this->authorizeDelete();
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
        $this->authorizeEdit();
        $validated = $this->validate();
        
        // Convert date from DD-MM-YYYY to YYYY-MM-DD format for database storage
        $hireDateForDb = \DateTime::createFromFormat('d-m-Y', $this->hire_date)->format('Y-m-d');
        
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
                'hire_date' => $hireDateForDb,
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
    
    // Import methods
    public function openImportModal()
    {
        $this->showImportModal = true;
        $this->importFile = null;
    }
    
    public function closeImportModal()
    {
        $this->showImportModal = false;
        $this->importFile = null;
    }
    
    public function importEmployee()
    {
        $this->authorizeEdit();
        $this->validate();
        
        try {
            Excel::import(new EmployeesImport, $this->importFile);
            $this->setPersistentMessage('Employee data imported successfully.', 'success');
            $this->closeImportModal();
        } catch (\Exception $e) {
            $this->setPersistentMessage('Error importing data: ' . $e->getMessage(), 'error');
        }
    }
    
    // Export methods
    public function exportToExcel()
    {
        $this->authorizeView();
        $export = new EmployeesExportWithHeaders($this->exportStartDate, $this->exportEndDate);
        
        $filename = 'employee_data_export_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download($export, $filename);
    }
    
    public function exportToPdf()
    {
        $this->authorizeView();
        $exporter = new EmployeesPdfExporter($this->exportStartDate, $this->exportEndDate);
        
        $html = $exporter->generate();
        
        // Ensure proper UTF-8 encoding with fallback
        if (function_exists('mb_convert_encoding')) {
            $html = mb_convert_encoding($html, 'UTF-8', 'auto');
        } else {
            $html = utf8_encode($html);
        }
        
        $filename = 'employee_data_export_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        // Create DomPDF instance
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return response()->make($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
