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
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
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
    public $status; // Keep for backward compatibility
    public $employment_status_id;
    public $hire_date;
    public $address;
    public $phone;
    public $email;
    
    public $departments = [];
    public $positions = [];
    public $family_compositions = [];
    public $employment_statuses = [];
    
    public $search = '';
    public $departmentFilter = null;

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
        'department' => 'required',
        'position' => 'required',
        'monthly_salary' => 'required|numeric',
        'hire_date' => 'required|date_format:d-m-Y',
        'status' => 'required',
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
        $validated = $this->validate($this->getValidationRules());

        // Convert date from DD-MM-YYYY to YYYY-MM-DD format for database storage
        $hireDateForDb = \DateTime::createFromFormat('d-m-Y', $this->hire_date)->format('Y-m-d');

        // Get department, position, and employment status for backward compatibility
        $dept = Department::find($this->department_id);
        $pos = Position::find($this->position_id);
        $famComp = FamilyComposition::find($this->family_composition_id);
        $empStatus = EmploymentStatus::find($this->employment_status_id);

        EmployeeModel::create([
            'ndp' => $this->ndp,
            'name' => $this->name,
            'department' => $dept?->name, // Keep for backward compatibility
            'department_id' => $this->department_id,
            'position' => $pos?->name, // Keep for backward compatibility
            'position_id' => $this->position_id,
            'grade' => $this->grade,
            'family_composition' => $famComp?->number, // Keep for backward compatibility
            'family_composition_id' => $this->family_composition_id,
            'monthly_salary' => $this->monthly_salary,
            'status' => $empStatus?->value, // Keep for backward compatibility
            'employment_status_id' => $this->employment_status_id,
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
        $this->department_id = null;
        $this->position = ''; // Keep for backward compatibility
        $this->position_id = null;
        $this->grade = '';
        $this->family_composition = 0; // Keep for backward compatibility
        $this->family_composition_id = null;
        $this->monthly_salary = '';
        $this->status = 'active'; // Keep for backward compatibility
        $this->employment_status_id = null;
        $this->hire_date = '';
        $this->address = '';
        $this->phone = '';
        $this->email = '';
    }

    public function filterEmployees()
    {
        $query = EmployeeModel::with(['department', 'position', 'familyComposition', 'employmentStatus'])
                              ->orderBy('name', 'asc');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('ndp', 'like', '%' . $this->search . '%')
                  ->orWhere('name', 'like', '%' . $this->search . '%')
                  ->orWhere('department', 'like', '%' . $this->search . '%')
                  ->orWhere('position', 'like', '%' . $this->search . '%')
                  ->orWhereHas('department', function($subQ) {
                      $subQ->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('position', function($subQ) {
                      $subQ->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->departmentFilter) {
            $query->where('department_id', $this->departmentFilter);
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
        $employee = EmployeeModel::with(['department', 'position', 'familyComposition', 'employmentStatus'])->find($id);
        if ($employee) {
            $this->editingId = $employee->id;
            $this->ndp = $employee->ndp;
            $this->name = $employee->name;
            $this->department = $employee->department; // Keep for backward compatibility
            $this->department_id = $employee->department_id;
            $this->position = $employee->position; // Keep for backward compatibility
            $this->position_id = $employee->position_id;
            $this->grade = $employee->grade;
            $this->family_composition = $employee->family_composition; // Keep for backward compatibility
            $this->family_composition_id = $employee->family_composition_id;
            $this->monthly_salary = $employee->monthly_salary;
            $this->status = $employee->status; // Keep for backward compatibility
            $this->employment_status_id = $employee->employment_status_id;
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
        try {
            if ($this->isEditing) {
                $this->updateEmployee();
            } else {
                $this->saveEmployee();
            }
            
            $this->closeCreateModal();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors will be automatically handled by Livewire
            // We just need to make sure the modal stays open so user can see errors
            $this->setPersistentMessage('Please check the form for validation errors.', 'error');
        } catch (\Exception $e) {
            $this->setPersistentMessage('Error: ' . $e->getMessage(), 'error');
            // Keep modal open so user can see the error
        }

        $this->closeCreateModal();
    }

    protected function getValidationRules()
    {
        $rules = [
            'ndp' => 'required|unique:employees,ndp',
            'name' => 'required',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'family_composition_id' => 'nullable|exists:family_compositions,id',
            'monthly_salary' => 'required|numeric',
            'hire_date' => 'required|date_format:d-m-Y',
            'employment_status_id' => 'required|exists:employment_statuses,id',
        ];

        // When editing, exclude current record from unique validation
        if ($this->isEditing && $this->editingId) {
            $rules['ndp'] = 'required|unique:employees,ndp,' . $this->editingId;
        }

        return $rules;
    }

    public function updateEmployee()
    {
        $this->authorizeEdit();
        $validated = $this->validate($this->getValidationRules());

        // Convert date from DD-MM-YYYY to YYYY-MM-DD format for database storage
        $hireDateForDb = \DateTime::createFromFormat('d-m-Y', $this->hire_date)->format('Y-m-d');

        // Get department, position, and employment status for backward compatibility
        $dept = Department::find($this->department_id);
        $pos = Position::find($this->position_id);
        $famComp = FamilyComposition::find($this->family_composition_id);
        $empStatus = EmploymentStatus::find($this->employment_status_id);

        $employee = EmployeeModel::find($this->editingId);
        if ($employee) {
            $employee->update([
                'ndp' => $this->ndp,
                'name' => $this->name,
                'department' => $dept?->name, // Keep for backward compatibility
                'department_id' => $this->department_id,
                'position' => $pos?->name, // Keep for backward compatibility
                'position_id' => $this->position_id,
                'grade' => $this->grade,
                'family_composition' => $famComp?->number, // Keep for backward compatibility
                'family_composition_id' => $this->family_composition_id,
                'monthly_salary' => $this->monthly_salary,
                'status' => $empStatus?->value, // Keep for backward compatibility
                'employment_status_id' => $this->employment_status_id,
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
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,xls,csv',
        ]);
        
        try {
            $import = new EmployeesImport();
            Excel::import($import, $this->importFile);
            
            $this->setPersistentMessage('Employee data imported successfully.', 'success');
            $this->closeImportModal();
        } catch (ExcelValidationException $e) {
            $failureMessages = [];
            $failures = $e->failures();

            foreach ($failures as $failure) {
                // Ensure all error messages are UTF-8 clean
                $row = $failure->row();
                $errors = $failure->errors();
                $cleanErrors = array_map(function($error) {
                    return mb_convert_encoding($error, 'UTF-8', 'UTF-8');
                }, $errors);
                $failureMessages[] = 'Row ' . $row . ': ' . implode(', ', $cleanErrors);
            }

            $errorMessage = 'Import failed with validation errors: ' . implode(' | ', $failureMessages);
            // Ensure the error message is UTF-8 clean
            $cleanErrorMessage = mb_convert_encoding($errorMessage, 'UTF-8', 'UTF-8');
            $this->setPersistentMessage($cleanErrorMessage, 'error');
        } catch (\Exception $e) {
            // Ensure exception message is UTF-8 clean
            $cleanMessage = mb_convert_encoding($e->getMessage(), 'UTF-8', 'UTF-8');
            $this->setPersistentMessage('Error importing data: ' . $cleanMessage, 'error');
        }
    }
    
    public function downloadSampleExcel()
    {
        // Create a sample CSV file and store it temporarily
        // Updated to match current table structure with foreign keys
        $sampleData = [
            ['ndp', 'name', 'department', 'position', 'grade', 'family_composition', 'monthly_salary', 'status', 'hire_date', 'address', 'phone', 'email'],
            ['NDP001', 'Budi Santoso', 'Finance', 'Manager', 'A', '3', '8000000', 'active', now()->format('Y-m-d'), 'Jl. Merdeka No. 123', '081234567890', 'budi@example.com'],
            ['NDP002', 'Siti Aminah', 'Production', 'Supervisor', 'B', '2', '6000000', 'active', now()->format('Y-m-d'), 'Jl. Sudirman No. 45', '082345678901', 'siti@example.com'],
            ['NDP003', 'Ahmad Fauzi', 'Sales', 'Staff', 'C', '1', '4500000', 'active', now()->format('Y-m-d'), 'Jl. Gatot Subroto No. 78', '083456789012', 'ahmad@example.com'],
        ];
        
        $csv = '';
        foreach ($sampleData as $row) {
            $csv .= '"' . implode('","', $row) . "\"\n";
        }
        
        // Save to a temporary file
        $filename = 'sample_employees_data.csv';
        $path = storage_path('app/temp/' . $filename);
        
        // Ensure the temp directory exists
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        file_put_contents($path, $csv);
        
        return response()->download($path)->deleteFileAfterSend(true);
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
        // Redirect to the dedicated PDF export controller route
        return redirect()->route('employees.export.pdf', [
            'start_date' => $this->exportStartDate,
            'end_date' => $this->exportEndDate,
        ]);
    }
}
