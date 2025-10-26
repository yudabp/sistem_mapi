<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Production as ProductionModel;
use App\Models\VehicleNumber;
use App\Models\Division;
use App\Models\Pks as PksModel;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Imports\ProductionImport;
use App\Exports\ProductionExportWithHeaders;
use App\Exports\ProductionPdfExporter;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Livewire\Concerns\WithRoleCheck;

class Production extends Component
{
    use WithFileUploads;
    use WithRoleCheck;

    public $transaction_number;
    public $date; // This will hold the DD-MM-YYYY format from the view
    public $dateFormatted; // This will hold the YYYY-MM-DD format for processing
    public $sp_number;
    public $vehicle_number; // Keep for backward compatibility
    public $vehicle_id;
    public $tbs_quantity;
    public $kg_quantity;
    public $division; // Keep for backward compatibility
    public $division_id;
    public $pks; // Keep for backward compatibility
    public $pks_id;
    public $sp_photo;
    
    public $vehicle_numbers = [];
    public $divisions = [];
    public $pks_list = [];
    
    public $search = '';
    public $dateFilter = '';
    public $divisionFilter = '';

    // Modal control
    public $showModal = false;
    public $isEditing = false;
    public $editingId = null;

    // Delete confirmation
    public $showDeleteConfirmation = false;
    public $deletingProductionId = null;
    public $deletingProductionName = '';

    // Photo viewing
    public $showPhotoModal = false;
    public $photoToView = null;

    // Metric filter
    public $metricFilter = 'all'; // Default to all time
    public $startDate = null;
    public $endDate = null;

    // Persistent message
    public $persistentMessage = '';
    public $messageType = 'success'; // success, error, warning, info
    
    // Import/Export properties
    public $importFile = null;
    public $exportStartDate = null;
    public $exportEndDate = null;
    public $showImportModal = false;

    protected $queryString = ['search', 'dateFilter', 'divisionFilter', 'metricFilter'];

    protected function rules()
    {
        $rules = [
            'date' => 'required|date',
            'sp_number' => 'required',
            'vehicle_id' => 'required|exists:vehicle_numbers,id',
            'tbs_quantity' => 'required|numeric',
            'kg_quantity' => 'required|numeric',
            'division_id' => 'required|exists:divisions,id',
            'pks_id' => 'required|exists:pks,id',
            'sp_photo' => 'nullable|image|max:10240', // Max 10MB
        ];

        // For transaction_number, add unique validation except for current record
        if ($this->isEditing) {
            $rules['transaction_number'] = 'required|unique:production,transaction_number,' . $this->editingId;
        } else {
            $rules['transaction_number'] = 'required|unique:production,transaction_number';
        }

        return $rules;
    }

    public function mount()
    {
        $this->mountWithRoleCheck();
        $this->loadOptions();
        // Set default export dates: start date 1 month ago, end date today
        if (!$this->exportStartDate) {
            $this->exportStartDate = now()->subMonth()->format('Y-m-d');
        }
        if (!$this->exportEndDate) {
            $this->exportEndDate = now()->format('Y-m-d');
        }
    }
    
    public function loadOptions()
    {
        $this->vehicle_numbers = VehicleNumber::where('is_active', true)->orderBy('number')->get();
        $this->divisions = Division::where('is_active', true)->orderBy('name')->get();
        $this->pks_list = PksModel::where('is_active', true)->orderBy('name')->get();
    }

    public function render()
    {
        $filteredProductions = $this->filterProductions();

        return view('livewire.production', [
            'productions' => $filteredProductions,
            'total_tbs' => $this->getTotalTbs(),
            'total_kg' => $this->getTotalKg(),
        ]);
    }

    public function saveProduction()
    {
        $this->authorizeEdit();

        $validated = $this->validate();
        
        // Convert date from DD-MM-YYYY to YYYY-MM-DD format for database storage
        $dateForDb = \DateTime::createFromFormat('d-m-Y', $this->date)->format('Y-m-d');
        
        // Handle file upload
        $photoPath = null;
        if ($this->sp_photo) {
            $photoPath = $this->sp_photo->store('sp_photos', 'public');
        }

        ProductionModel::create([
            'transaction_number' => $this->transaction_number,
            'date' => $dateForDb,
            'sp_number' => $this->sp_number,
            'vehicle_number' => $this->vehicle_number, // Keep for backward compatibility
            'vehicle_id' => $this->vehicle_id,
            'tbs_quantity' => $this->tbs_quantity,
            'kg_quantity' => $this->kg_quantity,
            'division' => $this->division, // Keep for backward compatibility
            'division_id' => $this->division_id,
            'pks' => $this->pks, // Keep for backward compatibility
            'pks_id' => $this->pks_id,
            'sp_photo_path' => $photoPath,
        ]);

        // Reset form
        $this->resetForm();
        $this->loadOptions();
        
        $this->setPersistentMessage('Production record created successfully.', 'success');
    }

    public function resetForm()
    {
        $this->transaction_number = '';
        $this->date = '';
        $this->sp_number = '';
        $this->vehicle_number = ''; // Keep for backward compatibility
        $this->vehicle_id = '';
        $this->tbs_quantity = '';
        $this->kg_quantity = '';
        $this->division = ''; // Keep for backward compatibility
        $this->division_id = '';
        $this->pks = ''; // Keep for backward compatibility
        $this->pks_id = '';
        $this->sp_photo = null;
    }

    public function filterProductions()
    {
        $query = ProductionModel::with(['vehicle', 'divisionRel', 'pksRel'])->orderBy('date', 'desc');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('sp_number', 'like', '%' . $this->search . '%')
                  ->orWhere('vehicle_number', 'like', '%' . $this->search . '%')
                  ->orWhere('division', 'like', '%' . $this->search . '%')
                  ->orWhere('transaction_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('vehicle', function($subQ) {
                      $subQ->where('vehicle_number', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('divisionRel', function($subQ) {
                      $subQ->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('pksRel', function($subQ) {
                      $subQ->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->dateFilter) {
            $query->whereYear('date', '=', substr($this->dateFilter, 0, 4))
                  ->whereMonth('date', '=', substr($this->dateFilter, 5, 2));
        }

        if ($this->divisionFilter) {
            $query->where('division', '=', $this->divisionFilter);
        }

        // Apply metric filter
        $query = $this->applyMetricFilter($query);

        return $query->get();
    }

    public function applyMetricFilter($query)
    {
        $now = now();
        
        switch ($this->metricFilter) {
            case 'today':
                $query->whereDate('date', $now->toDateString());
                break;
            case 'yesterday':
                $yesterday = $now->subDay();
                $query->whereDate('date', $yesterday->toDateString());
                break;
            case 'this_week':
                $query->whereBetween('date', [
                    $now->startOfWeek()->toDateString(),
                    $now->endOfWeek()->toDateString()
                ]);
                break;
            case 'last_week':
                $lastWeekStart = $now->subWeek()->startOfWeek();
                $lastWeekEnd = $now->subWeek()->endOfWeek();
                $query->whereBetween('date', [
                    $lastWeekStart->toDateString(),
                    $lastWeekEnd->toDateString()
                ]);
                break;
            case 'this_month':
                $query->whereYear('date', $now->year)
                      ->whereMonth('date', $now->month);
                break;
            case 'last_month':
                $lastMonth = $now->subMonth();
                $query->whereYear('date', $lastMonth->year)
                      ->whereMonth('date', $lastMonth->month);
                break;
            case 'custom':
                if ($this->startDate && $this->endDate) {
                    $query->whereBetween('date', [$this->startDate, $this->endDate]);
                }
                break;
            case 'all':
            default:
                // No additional filtering for 'all' option
                break;
        }
        
        return $query;
    }

    public function getTotalTbs()
    {
        $query = ProductionModel::query();
        $query = $this->applyMetricFilter($query);
        return $query->sum('tbs_quantity');
    }

    public function getTotalKg()
    {
        $query = ProductionModel::query();
        $query = $this->applyMetricFilter($query);
        return $query->sum('kg_quantity');
    }

    public function deleteProduction($id)
    {
        $this->authorizeDelete();

        $production = ProductionModel::find($id);
        if ($production) {
            // Delete the photo if it exists
            if ($production->sp_photo_path) {
                Storage::disk('public')->delete($production->sp_photo_path);
            }
            $production->delete();
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
        $production = ProductionModel::find($id);
        if ($production) {
            $this->editingId = $production->id;
            $this->transaction_number = $production->transaction_number;
            $this->date = $production->date->format('d-m-Y'); // Format for DD-MM-YYYY display
            $this->sp_number = $production->sp_number;
            $this->vehicle_number = $production->vehicle_number; // Keep for backward compatibility
            $this->vehicle_id = $production->vehicle_id;
            $this->tbs_quantity = $production->tbs_quantity;
            $this->kg_quantity = $production->kg_quantity;
            $this->division = $production->division; // Keep for backward compatibility
            $this->division_id = $production->division_id;
            $this->pks = $production->pks; // Keep for backward compatibility
            $this->pks_id = $production->pks_id;
            $this->sp_photo = null; // We don't load the file, just the path
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

    public function confirmDelete($id, $transaction_number)
    {
        $this->deletingProductionId = $id;
        $this->deletingProductionName = $transaction_number;
        $this->showDeleteConfirmation = true;
    }

    public function closeDeleteConfirmation()
    {
        $this->showDeleteConfirmation = false;
        $this->deletingProductionId = null;
        $this->deletingProductionName = '';
    }

    public function deleteProductionConfirmed()
    {
        $this->authorizeDelete();
        $production = ProductionModel::find($this->deletingProductionId);
        if ($production) {
            // Delete the photo if it exists
            if ($production->sp_photo_path) {
                Storage::disk('public')->delete($production->sp_photo_path);
            }
            $production->delete();
            $this->setPersistentMessage('Production record deleted successfully.', 'success');
        }
        
        $this->closeDeleteConfirmation();
    }

    public function saveProductionModal()
    {
        if ($this->isEditing) {
            $this->updateProduction();
        } else {
            $this->saveProduction();
        }
        
        $this->closeCreateModal();
    }

    public function updateProduction()
    {
        $this->authorizeEdit();

        $validated = $this->validate();

        $production = ProductionModel::find($this->editingId);
        if ($production) {
            // Convert date from DD-MM-YYYY to YYYY-MM-DD format for database storage
            $dateForDb = \DateTime::createFromFormat('d-m-Y', $this->date)->format('Y-m-d');
            
            // Handle file upload
            $photoPath = $production->sp_photo_path; // Keep existing path if no new file
            if ($this->sp_photo) {
                // Delete old photo if exists
                if ($production->sp_photo_path) {
                    Storage::disk('public')->delete($production->sp_photo_path);
                }
                $photoPath = $this->sp_photo->store('sp_photos', 'public');
            }

            $production->update([
                'transaction_number' => $this->transaction_number,
                'date' => $dateForDb,
                'sp_number' => $this->sp_number,
                'vehicle_number' => $this->vehicle_number, // Keep for backward compatibility
                'vehicle_id' => $this->vehicle_id,
                'tbs_quantity' => $this->tbs_quantity,
                'kg_quantity' => $this->kg_quantity,
                'division' => $this->division, // Keep for backward compatibility
                'division_id' => $this->division_id,
                'pks' => $this->pks, // Keep for backward compatibility
                'pks_id' => $this->pks_id,
                'sp_photo_path' => $photoPath,
            ]);

            $this->setPersistentMessage('Production record updated successfully.', 'success');
        }
    }

    public function showPhoto($path)
    {
        $this->photoToView = $path;
        $this->showPhotoModal = true;
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
    
    public function importProduction()
    {
        $this->authorizeEdit();

        $this->validate();
        
        try {
            Excel::import(new ProductionImport, $this->importFile);
            $this->setPersistentMessage('Production data imported successfully.', 'success');
            $this->closeImportModal();
        } catch (\Exception $e) {
            $this->setPersistentMessage('Error importing data: ' . $e->getMessage(), 'error');
        }
    }
    
    public function downloadSampleExcel()
    {
        // Create a sample CSV file and store it temporarily
        $sampleData = [
            ['transaction_number', 'date', 'sp_number', 'vehicle_number', 'tbs_quantity', 'kg_quantity', 'division', 'pks'],
            ['TRX001', now()->format('Y-m-d'), 'SP001', 'B1234XYZ', '1000.50', '950.20', 'Afdeling A', 'PKS 1'],
            ['TRX002', now()->format('Y-m-d'), 'SP002', 'B5678XYZ', '1200.75', '1140.80', 'Afdeling B', 'PKS 2'],
            ['TRX003', now()->format('Y-m-d'), 'SP003', 'B9012XYZ', '950.25', '902.75', 'Afdeling C', 'PKS 3'],
        ];
        
        $csv = '';
        foreach ($sampleData as $row) {
            $csv .= '"' . implode('","', $row) . "\"\n";
        }
        
        // Save to a temporary file
        $filename = 'sample_production_data.csv';
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

        $export = new ProductionExportWithHeaders($this->exportStartDate, $this->exportEndDate);
        
        $filename = 'production_data_export_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download($export, $filename);
    }
    
    public function exportToPdf()
    {
        $this->authorizeView();

        $exporter = new ProductionPdfExporter($this->exportStartDate, $this->exportEndDate);
        
        $html = $exporter->generate();
        
        // Ensure proper UTF-8 encoding with fallback
        if (function_exists('mb_convert_encoding')) {
            $html = mb_convert_encoding($html, 'UTF-8', 'auto');
        } else {
            $html = utf8_encode($html);
        }
        
        $filename = 'production_data_export_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        
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
