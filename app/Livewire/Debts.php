<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Debt as DebtModel;
use App\Models\HutangPembayaran;
use App\Models\MasterDebtType;
use App\Services\DebtPaymentService;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Imports\DebtsImport;
use App\Exports\DebtsExportWithHeaders;
use App\Exports\DebtsPdfExporter;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use App\Livewire\Concerns\WithRoleCheck;

class Debts extends Component
{
    use WithFileUploads;
    use WithRoleCheck;

    public $amount;
    public $sisa_hutang;
    public $cicilan_per_bulan;
    public $creditor;
    public $due_date;
    public $description;
    public $debt_type_id;
    public $employee_id;
    public $proof_document;
    public $status;
    public $paid_date;

    // Additional fields for payment history
    public $debtTypes = [];
    public $employees = [];
    public $showPaymentHistory = false;
    public $selectedDebtId = null;
    public $selectedDebtPayments = [];
    
    public $search = '';
    public $statusFilter = '';

    // Modal control
    public $showModal = false;
    public $isEditing = false;
    public $editingId = null;

    // Delete confirmation
    public $showDeleteConfirmation = false;
    public $deletingDebtId = null;
    public $deletingDebtName = '';

    // Photo viewing
    public $showPhotoModal = false;
    public $photoToView = null;

    // Persistent message
    public $persistentMessage = '';
    public $messageType = 'success'; // success, error, warning, info
    
    protected $queryString = ['search', 'statusFilter'];

    public $importFile = null;
    public $exportStartDate = null;
    public $exportEndDate = null;
    public $showImportModal = false;

    protected $rules = [
        'amount' => 'required|numeric|min:0',
        'creditor' => 'required|string|max:255',
        'debt_type_id' => 'required|exists:master_debt_types,id',
        'due_date' => 'nullable|date_format:d-m-Y',
        'description' => 'nullable|string',
        'employee_id' => 'nullable|exists:employees,id',
        'cicilan_per_bulan' => 'nullable|numeric|min:0',
        'proof_document' => 'nullable|file|max:10240', // Max 10MB
        'importFile' => 'nullable|file|mimes:xlsx,xls,csv', // Make importFile nullable for regular form operations
    ];

    // Validation rules for import
    protected $importRules = [
        'importFile' => 'required|file|mimes:xlsx,xls,csv',
    ];

    /**
     * Get the validation rules that apply to the request.
     */
    protected function rules()
    {
        $rules = $this->rules;

        // If debt type is Hutang Karyawan (id = 3), make employee_id required
        if ($this->debt_type_id == 3) {
            $rules['employee_id'] = 'required|exists:employees,id';
            $rules['creditor'] = 'nullable|string|max:255'; // Creditor can be auto-filled from employee
        }

        return $rules;
    }

    public function mount()
    {
        $this->mountWithRoleCheck();
        // Clear any existing persistent messages
        $this->clearPersistentMessage();

        // Set default export dates: start date 1 month ago, end date today in DD-MM-YYYY format
        if (!$this->exportStartDate) {
            $this->exportStartDate = now()->subMonth()->format('d-m-Y');
        }
        if (!$this->exportEndDate) {
            $this->exportEndDate = now()->format('d-m-Y');
        }

        $this->loadDebtTypes();
        $this->loadEmployees();
    }

    public function render()
    {
        $filteredDebts = $this->filterDebts()->load(['debtType', 'payments', 'employee']);

        return view('livewire.debts', [
            'debts' => $filteredDebts,
            'debtTypes' => $this->debtTypes,
            'employees' => $this->employees,
            'total_debt' => $filteredDebts->sum('amount'),
            'total_paid_amount' => $filteredDebts->sum(function($debt) { return $debt->total_paid; }),
            'total_remaining_amount' => $filteredDebts->sum(function($debt) { return $debt->remaining_debt; }),
            'unpaid_debts' => $filteredDebts->where('status', 'unpaid')->count(),
            'paid_debts' => $filteredDebts->where('status', 'paid')->count(),
        ]);
    }

    /**
     * Load debt types for dropdown
     */
    public function loadDebtTypes()
    {
        $this->debtTypes = MasterDebtType::active()->get();
    }

    /**
     * Load employees for dropdown
     */
    public function loadEmployees()
    {
        $this->employees = \App\Models\Employee::select('id', 'name', 'ndp')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
    }

    /**
     * Update creditor when employee is selected
     */
    public function updatedEmployeeId($value)
    {
        if ($this->debt_type_id == 3 && $value) {
            $employee = \App\Models\Employee::find($value);
            if ($employee) {
                $this->creditor = $employee->name;
            }
        }
    }

    /**
     * Auto-fill creditor when debt type changes to Hutang Karyawan
     */
    public function updatedDebtTypeId($value)
    {
        if ($value != 3) {
            $this->employee_id = null;
            // Don't reset creditor if user already entered it
        }
    }

    /**
     * Get debt payment service
     */
    private function getDebtPaymentService()
    {
        return app(DebtPaymentService::class);
    }

    public function saveDebt()
    {
        $this->authorizeEdit();
        try {
            $this->setPersistentMessage('Starting save process...', 'info');

            // Prepare data for validation - convert empty strings to null for numeric fields
            $this->cicilan_per_bulan = !empty($this->cicilan_per_bulan) ? $this->cicilan_per_bulan : null;

            $validated = $this->validate();
            $this->setPersistentMessage('Validation passed', 'info');

            // Convert date from DD-MM-YYYY to YYYY-MM-DD format for database storage
            $dueDateForDb = \DateTime::createFromFormat('d-m-Y', $this->due_date)->format('Y-m-d');

            // Handle file upload
            $proofPath = null;
            if ($this->proof_document) {
                $proofPath = $this->proof_document->store('debt_proofs', 'public');
            }

            DebtModel::create([
                'amount' => $this->amount,
                'sisa_hutang' => $this->amount, // Initially, remaining debt equals total amount
                'cicilan_per_bulan' => !empty($this->cicilan_per_bulan) ? $this->cicilan_per_bulan : null,
                'creditor' => $this->creditor,
                'due_date' => $dueDateForDb,
                'description' => $this->description,
                'debt_type_id' => $this->debt_type_id ?? null,
                'employee_id' => $this->debt_type_id == 3 ? $this->employee_id : null,
                'proof_document_path' => $proofPath,
                'status' => 'unpaid', // Default to unpaid
                'paid_date' => null,
            ]);

            // Reset form
            $this->resetForm();

            $this->setPersistentMessage('Debt record created successfully.', 'success');
            return true;
        } catch (\Exception $e) {
            $this->setPersistentMessage('Error: ' . $e->getMessage(), 'error');
            return false;
        }
    }

    public function resetForm()
    {
        $this->amount = '';
        $this->sisa_hutang = '';
        $this->cicilan_per_bulan = null;
        $this->creditor = '';
        $this->due_date = '';
        $this->description = '';
        $this->debt_type_id = null;
        $this->employee_id = null;
        $this->proof_document = null;
        $this->status = 'unpaid';
        $this->paid_date = '';
    }

    public function filterDebts()
    {
        $query = DebtModel::orderBy('due_date', 'desc');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('creditor', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', '=', $this->statusFilter);
        }

        return $query->get();
    }

    public function markAsPaid($id)
    {
        $debt = DebtModel::find($id);
        if ($debt) {
            $debt->update([
                'status' => 'paid',
                'sisa_hutang' => 0,
                'paid_date' => now(),
            ]);
            $this->setPersistentMessage('Debt marked as paid successfully.', 'success');
        }
    }

    /**
     * Show payment history for a specific debt
     */
    public function showPaymentHistory($debtId)
    {
        $this->selectedDebtId = $debtId;
        $this->selectedDebtPayments = $this->getDebtPaymentService()->getPaymentHistory($debtId);
        $this->showPaymentHistory = true;
    }

    /**
     * Hide payment history modal
     */
    public function hidePaymentHistory()
    {
        $this->showPaymentHistory = false;
        $this->selectedDebtId = null;
        $this->selectedDebtPayments = [];
    }

    /**
     * Get total paid amount for a debt
     */
    public function getTotalPaidAmount($debtId)
    {
        $debt = DebtModel::find($debtId);
        return $debt ? $debt->total_paid : 0;
    }

    /**
     * Get remaining amount for a debt
     */
    public function getRemainingAmount($debtId)
    {
        $debt = DebtModel::find($debtId);
        return $debt ? $debt->remaining_debt : 0;
    }

    /**
     * Calculate payment percentage for a debt
     */
    public function getPaymentPercentage($debtId)
    {
        $debt = DebtModel::find($debtId);
        if (!$debt || $debt->amount == 0) return 0;

        return round(($debt->total_paid / $debt->amount) * 100, 2);
    }

    /**
     * Check if debt is overdue
     */
    public function isOverdue($debt)
    {
        return $debt->status === 'unpaid' && $debt->due_date < now();
    }

    /**
     * Get overdue days for a debt
     */
    public function getOverdueDays($debt)
    {
        if (!$this->isOverdue($debt)) return 0;

        return now()->diffInDays($debt->due_date);
    }

    public function deleteDebt($id)
    {
        $debt = DebtModel::find($id);
        if ($debt) {
            // Delete the proof if it exists
            if ($debt->proof_document_path) {
                Storage::disk('public')->delete($debt->proof_document_path);
            }
            $debt->delete();
        }
    }

    // Modal methods
    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
        // Ensure delete confirmation is closed when opening create modal
        $this->showDeleteConfirmation = false;
        $this->deletingDebtId = null;
        $this->deletingDebtName = '';
        // Clear any persistent messages
        $this->clearPersistentMessage();
    }

    public function openEditModal($id)
    {
        $debt = DebtModel::find($id);
        if ($debt) {
            $this->editingId = $debt->id;
            $this->amount = $debt->amount;
            $this->sisa_hutang = $debt->sisa_hutang;
            $this->cicilan_per_bulan = $debt->cicilan_per_bulan;
            $this->creditor = $debt->creditor;
            $this->due_date = $debt->due_date->format('d-m-Y'); // Format for DD-MM-YYYY display
            $this->description = $debt->description;
            $this->debt_type_id = $debt->debt_type_id;
            $this->employee_id = $debt->employee_id;
            $this->proof_document = null; // We don't load the file, just the path
            $this->status = $debt->status;
            $this->paid_date = $debt->paid_date ? $debt->paid_date->format('d-m-Y') : null; // Format for DD-MM-YYYY display
            $this->isEditing = true;
            $this->showModal = true;
            // Ensure delete confirmation is closed when opening edit modal
            $this->showDeleteConfirmation = false;
            $this->deletingDebtId = null;
            $this->deletingDebtName = '';
            // Clear any persistent messages
            $this->clearPersistentMessage();
        }
    }

    public function closeCreateModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->isEditing = false;
        $this->editingId = null;
    }

    public function confirmDelete($id, $creditor)
    {
        $this->deletingDebtId = $id;
        $this->deletingDebtName = $creditor;
        $this->showDeleteConfirmation = true;
    }

    public function closeDeleteConfirmation()
    {
        $this->showDeleteConfirmation = false;
        $this->deletingDebtId = null;
        $this->deletingDebtName = '';
    }

    public function deleteDebtConfirmed()
    {
        $this->authorizeDelete();
        $debt = DebtModel::find($this->deletingDebtId);
        if ($debt) {
            // Delete the proof if it exists
            if ($debt->proof_document_path) {
                Storage::disk('public')->delete($debt->proof_document_path);
            }
            $debt->delete();
            $this->setPersistentMessage('Debt record deleted successfully.', 'success');
        }
        
        $this->closeDeleteConfirmation();
    }

    public function saveDebtModal()
    {
        try {
            // Check which branch we're taking
            if ($this->isEditing) {
                $this->setPersistentMessage('Updating existing debt...', 'info');
                $result = $this->updateDebt();
            } else {
                $this->setPersistentMessage('Creating new debt...', 'info');
                $result = $this->saveDebt();
            }

            // Only close modal if operation was successful
            if ($result) {
                $this->closeCreateModal();
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors will be automatically handled by Livewire
            // We just need to make sure the modal stays open so user can see errors
            $this->setPersistentMessage('Please check the form for validation errors.', 'error');
        } catch (\Exception $e) {
            $this->setPersistentMessage('Error: ' . $e->getMessage(), 'error');
            // Keep modal open so user can see the error
        }
    }

    public function updateDebt()
    {
        $this->authorizeEdit();
        try {
            // Prepare data for validation - convert empty strings to null for numeric fields
            $this->cicilan_per_bulan = !empty($this->cicilan_per_bulan) ? $this->cicilan_per_bulan : null;

            $validated = $this->validate();

            // Convert dates from DD-MM-YYYY to YYYY-MM-DD format for database storage
            $dueDateForDb = \DateTime::createFromFormat('d-m-Y', $this->due_date)->format('Y-m-d');
            $paidDateForDb = $this->paid_date ? \DateTime::createFromFormat('d-m-Y', $this->paid_date)->format('Y-m-d') : null;

            $debt = DebtModel::find($this->editingId);
            if (!$debt) {
                $this->setPersistentMessage('Debt record not found.', 'error');
                return false;
            }

            // Handle file upload
            $proofPath = $debt->proof_document_path; // Keep existing path if no new file
            if ($this->proof_document) {
                // Delete old proof if exists
                if ($debt->proof_document_path) {
                    Storage::disk('public')->delete($debt->proof_document_path);
                }
                $proofPath = $this->proof_document->store('debt_proofs', 'public');
            }

            $debt->update([
                'amount' => $this->amount,
                'sisa_hutang' => $this->sisa_hutang,
                'cicilan_per_bulan' => !empty($this->cicilan_per_bulan) ? $this->cicilan_per_bulan : null,
                'creditor' => $this->creditor,
                'due_date' => $dueDateForDb,
                'description' => $this->description,
                'debt_type_id' => $this->debt_type_id,
                'employee_id' => $this->debt_type_id == 3 ? $this->employee_id : null,
                'proof_document_path' => $proofPath,
                'status' => $this->status,
                'paid_date' => $paidDateForDb,
            ]);

            $this->setPersistentMessage('Debt record updated successfully.', 'success');
            return true;
        } catch (\Exception $e) {
            $this->setPersistentMessage('Error: ' . $e->getMessage(), 'error');
            return false;
        }
    }

    public function showPhoto($path)
    {
        $this->photoToView = $path;
        $this->showPhotoModal = true;
    }

    public function closePhotoModal()
    {
        $this->showPhotoModal = false;
        $this->photoToView = null;
    }

    public function setPersistentMessage($message, $type = 'success')
    {
        // Translate common messages to Indonesian
        $translations = [
            'Starting save process...' => 'Memulai proses penyimpanan...',
            'Validation passed' => 'Validasi berhasil',
            'Debt record created successfully.' => 'Data hutang berhasil ditambahkan.',
            'Debt marked as paid successfully.' => 'Hutang berhasil ditandai sebagai lunas.',
            'Debt record deleted successfully.' => 'Data hutang berhasil dihapus.',
            'Updating existing debt...' => 'Memperbarui data hutang yang ada...',
            'Creating new debt...' => 'Membuat data hutang baru...',
            'Please check the form for validation errors.' => 'Silakan periksa formulir untuk kesalahan validasi.',
            'Error: ' => 'Terjadi kesalahan: ',
        ];

        $this->persistentMessage = str_replace(array_keys($translations), array_values($translations), $message);
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
    
    public function importDebt()
    {
        $this->authorizeEdit();
        $this->validate($this->importRules);

        try {
            $import = new DebtsImport();
            Excel::import($import, $this->importFile);
            
            $this->setPersistentMessage('Debt data imported successfully.', 'success');
            $this->closeImportModal();
        } catch (ExcelValidationException $e) {
            $failureMessages = [];
            $failures = $e->failures();

            foreach ($failures as $failure) {
                $failureMessages[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }

            $errorMessage = 'Import failed with validation errors: ' . implode(' | ', $failureMessages);
            $this->setPersistentMessage($errorMessage, 'error');
        } catch (\Exception $e) {
            $this->setPersistentMessage('Error importing data: ' . $e->getMessage(), 'error');
        }
    }
    
    public function downloadSampleExcel()
    {
        // Create a sample CSV file and store it temporarily
        // Updated to reflect required fields: amount, creditor, debt_type
        $sampleData = [
            ['amount', 'sisa_hutang', 'cicilan_per_bulan', 'creditor', 'debt_type', 'due_date', 'description', 'status', 'paid_date'],
            ['50000000', '50000000', '10000000', 'Bank Mandiri', 'Hutang Bank', now()->addMonth()->format('Y-m-d'), 'Pembelian alat produksi', 'unpaid', ''],
            ['25000000', '15000000', '5000000', 'Supplier A', 'Hutang Supplier', now()->addDays(15)->format('Y-m-d'), 'Pembelian bahan baku', 'unpaid', ''],
            ['15000000', '0', '0', 'PT. Karyawan Sejahtera', 'Hutang Karyawan', '', 'Bonus tahunan', 'paid', now()->format('Y-m-d')],
        ];
        
        $csv = '';
        foreach ($sampleData as $row) {
            $csv .= '"' . implode('","', $row) . "\"\n";
        }
        
        // Save to a temporary file
        $filename = 'sample_debts_data.csv';
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
        $export = new DebtsExportWithHeaders($this->exportStartDate, $this->exportEndDate);
        
        $filename = 'debt_data_export_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download($export, $filename);
    }
    
    public function exportToPdf()
    {
        $this->authorizeView();
        // Redirect to the dedicated PDF export controller route
        return redirect()->route('debts.export.pdf', [
            'start_date' => $this->exportStartDate,
            'end_date' => $this->exportEndDate,
        ]);
    }

    /**
     * Intercept property updates to handle empty values
     */
    public function updatedDebtTypeId($value)
    {
        $this->debt_type_id = $value === '' ? null : $value;
    }

    public function updatedEmployeeId($value)
    {
        $this->employee_id = $value === '' ? null : $value;
    }
}
