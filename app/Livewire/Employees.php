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
    public $persistentMessage = '';       // For modal messages (form related)
    public $messageType = 'success';      // For modal messages (form related)
    public $pageMessage = '';             // For page messages (non-form related)
    public $pageMessageType = 'success';  // For page messages (non-form related)

    public $perPage = 5;
    public $page = 1;
    
    protected $rules = [
        'name' => 'required',
        'position_id' => 'required|exists:positions,id',
        'monthly_salary' => 'required|numeric',
    ];

    protected $queryString = ['search', 'departmentFilter', 'perPage', 'page'];

    public $importFile = null;
    public $exportStartDate = null;
    public $exportEndDate = null;
    public $showImportModal = false;

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
        
        // Calculate totals separately for all matching records
        $query = EmployeeModel::orderBy('name', 'asc');
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

        $total_employees = $query->count();
        $total_salary = $query->sum('monthly_salary');

        return view('livewire.employees', [
            'employees' => $filteredEmployees,
            'total_employees' => $total_employees,
            'total_salary' => $total_salary,
        ]);
    }
    
    public function loadOptions()
    {
        $this->departments = Department::where('is_active', true)->orderBy('name')->get();
        $this->positions = Position::where('is_active', true)->orderBy('name')->get();
        $this->family_compositions = FamilyComposition::where('is_active', true)->orderBy('name')->get();
        $this->employment_statuses = EmploymentStatus::where('is_active', true)->orderBy('name')->get();
    }

    public function saveEmployee()
    {
        $this->authorizeEdit();
        $validated = $this->validate($this->getValidationRules());

        // Safely convert date from DD-MM-YYYY to YYYY-MM-DD format for database storage
        $hireDateForDb = null;
        if ($this->hire_date) {
            $dateObj = \DateTime::createFromFormat('d-m-Y', $this->hire_date);
            if ($dateObj !== false) {
                $hireDateForDb = $dateObj->format('Y-m-d');
            }
        }

        // Get department, position, and employment status for backward compatibility
        $dept = $this->department_id ? Department::find($this->department_id) : null;
        $pos = $this->position_id ? Position::find($this->position_id) : null;
        $famComp = $this->family_composition_id ? FamilyComposition::find($this->family_composition_id) : null;
        $empStatus = $this->employment_status_id ? EmploymentStatus::find($this->employment_status_id) : null;

        // Handle empty strings by converting to null
        $departmentId = $this->department_id ?: null;
        $positionId = $this->position_id ?: null;
        $familyCompositionId = $this->family_composition_id ?: null;
        $employmentStatusId = $this->employment_status_id ?: null;

        EmployeeModel::create([
            'ndp' => $this->ndp,
            'name' => $this->name,
            'department' => $dept?->name, // Keep for backward compatibility
            'department_id' => $departmentId,
            'position' => $pos?->name, // Keep for backward compatibility
            'position_id' => $positionId,
            'grade' => $this->grade,
            'family_composition' => $famComp?->name, // Keep for backward compatibility
            'family_composition_id' => $familyCompositionId,
            'monthly_salary' => $this->monthly_salary,
            'status' => $empStatus?->value, // Keep for backward compatibility
            'employment_status_id' => $employmentStatusId,
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
        $this->family_composition = ''; // Keep for backward compatibility
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

        // Use the component's page value to ensure correct pagination
        $paginator = $query->paginate($this->perPage, ['*'], 'page', $this->page ?: request()->get('page', 1));
        
        // Maintain the current page in the pagination links
        $paginator->withPath('/data-karyawan');
        
        return $paginator;
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
        // Store current page to maintain pagination state
        $currentPage = $this->page;
        
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
        
        // Restore page to maintain pagination state
        $this->page = $currentPage;
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
            $this->hire_date = $employee->hire_date ? $employee->hire_date->format('d-m-Y') : null; // Format for DD-MM-YYYY display, handle null
            $this->address = $employee->address;
            $this->phone = $employee->phone;
            $this->email = $employee->email;
            $this->isEditing = true;
            $this->showModal = true;
        }
    }

    public function closeCreateModal()
    {
        // Store current page to maintain pagination state
        $currentPage = $this->page;
        
        $this->showModal = false;
        $this->resetForm();
        $this->isEditing = false;
        $this->editingId = null;
        
        // Restore page after modal closes to maintain pagination state
        $this->page = $currentPage;
    }

    public function confirmDelete($id, $name)
    {
        $this->deletingEmployeeId = $id;
        $this->deletingEmployeeName = $name;
        $this->showDeleteConfirmation = true;
    }

    public function closeDeleteConfirmation()
    {
        // Store current page to maintain pagination state
        $currentPage = $this->page;
        
        $this->showDeleteConfirmation = false;
        $this->deletingEmployeeId = null;
        $this->deletingEmployeeName = '';
        
        // Restore page after confirmation closes to maintain pagination state
        $this->page = $currentPage;
    }

    public function deleteEmployeeConfirmed()
    {
        // Store current page before deletion to maintain pagination state after confirmation closes
        $currentPage = $this->page;
        
        $this->authorizeDelete();
        $employee = EmployeeModel::find($this->deletingEmployeeId);
        if ($employee) {
            $employee->delete();
            $this->setPageMessage('Employee record deleted successfully.', 'success');
        }
        
        $this->closeDeleteConfirmation();
        
        // Restore page after confirmation closes to maintain pagination state
        $this->page = $currentPage;
    }

    public function saveEmployeeModal()
    {
        // Store current page before saving to maintain pagination state after modal closes
        $currentPage = $this->page;
        
        if ($this->isEditing) {
            $this->updateEmployee();
        } else {
            $this->saveEmployee();
        }

        $this->setPersistentMessage('Employee record saved successfully.', 'success');
        $this->closeCreateModal();
        
        // Restore page after modal closes to maintain pagination state
        $this->page = $currentPage;
    }

    protected function getValidationRules()
    {
        $rules = [
            'ndp' => 'nullable|unique:employees,ndp',
            'name' => 'required',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'family_composition_id' => 'nullable|exists:family_compositions,id',
            'monthly_salary' => 'required|numeric',
            'hire_date' => 'nullable|date_format:d-m-Y',
            'employment_status_id' => 'nullable|exists:employment_statuses,id',
        ];

        // When editing, exclude current record from unique validation
        if ($this->isEditing && $this->editingId) {
            $rules['ndp'] = 'nullable|unique:employees,ndp,' . $this->editingId;
        }

        return $rules;
    }

    public function updateEmployee()
    {
        $this->authorizeEdit();
        $validated = $this->validate($this->getValidationRules());

        // Safely convert date from DD-MM-YYYY to YYYY-MM-DD format for database storage
        $hireDateForDb = null;
        if ($this->hire_date) {
            $dateObj = \DateTime::createFromFormat('d-m-Y', $this->hire_date);
            if ($dateObj !== false) {
                $hireDateForDb = $dateObj->format('Y-m-d');
            }
        }

        // Get department, position, and employment status for backward compatibility
        $dept = $this->department_id ? Department::find($this->department_id) : null;
        $pos = $this->position_id ? Position::find($this->position_id) : null;
        $famComp = $this->family_composition_id ? FamilyComposition::find($this->family_composition_id) : null;
        $empStatus = $this->employment_status_id ? EmploymentStatus::find($this->employment_status_id) : null;

        // Handle empty strings by converting to null
        $departmentId = $this->department_id ?: null;
        $positionId = $this->position_id ?: null;
        $familyCompositionId = $this->family_composition_id ?: null;
        $employmentStatusId = $this->employment_status_id ?: null;

        $employee = EmployeeModel::find($this->editingId);
        if ($employee) {
            $employee->update([
                'ndp' => $this->ndp,
                'name' => $this->name,
                'department' => $dept?->name, // Keep for backward compatibility
                'department_id' => $departmentId,
                'position' => $pos?->name, // Keep for backward compatibility
                'position_id' => $positionId,
                'grade' => $this->grade,
                'family_composition' => $famComp?->name, // Keep for backward compatibility
                'family_composition_id' => $familyCompositionId,
                'monthly_salary' => $this->monthly_salary,
                'status' => $empStatus?->value, // Keep for backward compatibility
                'employment_status_id' => $employmentStatusId,
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
        // Translate common messages to Indonesian
        $translations = [
            'Employee record created successfully.' => 'Data karyawan berhasil ditambahkan.',
            'Employee record updated successfully.' => 'Data karyawan berhasil diperbarui.',
            'Employee record deleted successfully.' => 'Data karyawan berhasil dihapus.',
            'Please check the form for validation errors.' => 'Silakan periksa formulir untuk kesalahan validasi.',
            'Error: ' => 'Terjadi kesalahan: ',
            'Employee data imported successfully.' => 'Data karyawan berhasil diimpor.',
            'Error importing data: ' => 'Terjadi kesalahan saat mengimpor data: ',
            'Import failed with validation errors: ' => 'Impor gagal dengan kesalahan validasi: ',
        ];

        $this->persistentMessage = str_replace(array_keys($translations), array_values($translations), $message);
        $this->messageType = $type;
    }

    public function clearPersistentMessage()
    {
        $this->persistentMessage = '';
    }
    
    public function setPageMessage($message, $type = 'success')
    {
        // Translate common messages to Indonesian
        $translations = [
            'Employee record deleted successfully.' => 'Data karyawan berhasil dihapus.',
            'Error: ' => 'Terjadi kesalahan: ',
        ];

        $this->pageMessage = str_replace(array_keys($translations), array_values($translations), $message);
        $this->pageMessageType = $type;
    }

    public function clearPageMessage()
    {
        $this->pageMessage = '';
        $this->pageMessageType = '';
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
        // Only name, position, and monthly_salary are required; rest are optional
        $sampleData = [
            ['ndp', 'name', 'department', 'position', 'grade', 'family_composition', 'monthly_salary', 'status', 'hire_date', 'address', 'phone', 'email'],
            ['NDP001', 'Budi Santoso', 'Finance', 'Manager', 'A', '3', '8000000', 'active', now()->format('Y-m-d'), 'Jl. Merdeka No. 123', '081234567890', 'budi@example.com'],
            ['NDP002', 'Siti Aminah', 'Production', 'Supervisor', '', '2', '6000000', 'active', now()->format('Y-m-d'), 'Jl. Sudirman No. 45', '082345678901', 'siti@example.com'],
            ['NDP003', 'Ahmad Fauzi', '', 'Staff', 'C', '', '4500000', 'active', '', '', '', ''],
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
