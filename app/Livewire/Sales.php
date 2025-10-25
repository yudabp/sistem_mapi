<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale as SaleModel;
use App\Models\Production as ProductionModel;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Imports\SalesImport;
use App\Exports\SalesExportWithHeaders;
use App\Exports\SalesPdfExporter;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class Sales extends Component
{
    use WithFileUploads;

    public $sp_number; // Keep for backward compatibility
    public $production_id;
    public $tbs_quantity;
    public $kg_quantity;
    public $price_per_kg;
    public $total_amount;
    public $sales_proof;
    public $sale_date;
    public $customer_name;
    public $customer_address;
    public $is_taxable = false;
    public $tax_percentage = 11.00;
    public $tax_amount = 0;
    
    // Autocomplete properties
    public $sp_search = '';
    public $spSuggestions = [];
    public $showSpSuggestions = false;
    
    public $search = '';
    public $dateFilter = '';

    // Modal control
    public $showModal = false;
    public $isEditing = false;
    public $editingId = null;

    // Delete confirmation
    public $showDeleteConfirmation = false;
    public $deletingSaleId = null;
    public $deletingSaleName = '';

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

    // Export filter
    public $exportFilter = 'all'; // all, taxable, non_taxable

    protected $queryString = ['search', 'dateFilter', 'metricFilter'];

    public $importFile = null;
    public $exportStartDate = null;
    public $exportEndDate = null;
    public $showImportModal = false;

    protected $rules = [
        'sp_number' => 'required|string|max:255',
        'production_id' => 'nullable|exists:production,id',
        'kg_quantity' => 'required|numeric',
        'price_per_kg' => 'required|numeric',
        'sale_date' => 'required|date_format:d-m-Y',
        'customer_name' => 'required',
        'customer_address' => 'required',
        'sales_proof' => 'nullable|image|max:10240', // Max 10MB
        'is_taxable' => 'boolean',
        'tax_percentage' => 'required|numeric|min:0|max:100',
        'tax_amount' => 'required|numeric|min:0',
    ];

    protected function rules()
    {
        $rules = $this->rules;
        
        // If not taxable, make tax fields optional
        if (!$this->is_taxable) {
            $rules['tax_percentage'] = 'nullable|numeric|min:0|max:100';
            $rules['tax_amount'] = 'nullable|numeric|min:0';
        }
        
        return $rules;
    }

    public function mount()
    {
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
        $filteredSales = $this->filterSales();

        return view('livewire.sales', [
            'sales' => $filteredSales,
            'total_kg' => $this->getTotalKg(),
            'total_sales' => $this->getTotalSales(),
        ]);
    }

    public function updatedPricePerKg()
    {
        $this->calculateTotal();
        $this->calculateTax();
    }

    public function updatedKgQuantity()
    {
        $this->calculateTotal();
        $this->calculateTax();
    }

    public function updatedSpNumber()
    {
        // Keep for backward compatibility
        if ($this->sp_number) {
            $production = ProductionModel::where('sp_number', $this->sp_number)->first();
            if ($production) {
                $this->production_id = $production->id;
                $this->tbs_quantity = $production->tbs_quantity;
                $this->kg_quantity = $production->kg_quantity;
                
                // Auto-calculate total amount if price per kg is already set
                $this->calculateTotal();
            } else {
                // Reset if production not found
                $this->production_id = '';
                $this->tbs_quantity = '';
                $this->kg_quantity = '';
                $this->total_amount = 0;
            }
        } else {
            $this->production_id = '';
            $this->tbs_quantity = '';
            $this->kg_quantity = '';
            $this->total_amount = 0;
        }
        $this->calculateTax();
    }



    public function updatedIsTaxable()
    {
        $this->calculateTax();
    }

    public function updatedTaxPercentage()
    {
        $this->calculateTax();
    }

    public function updatedTotalAmount()
    {
        $this->calculateTax();
    }

    public function updatedSpSearch()
    {
        if (strlen($this->sp_search) >= 2) {
            $this->spSuggestions = ProductionModel::where('sp_number', 'like', '%' . $this->sp_search . '%')
                ->select('id', 'sp_number', 'tbs_quantity', 'kg_quantity')
                ->orderBy('sp_number')
                ->limit(10)
                ->get()
                ->toArray();
            $this->showSpSuggestions = true;
        } else {
            $this->spSuggestions = [];
            $this->showSpSuggestions = false;
        }
    }

    public function selectSpSuggestion($spData)
    {
        $production = ProductionModel::find($spData['id']);
        if ($production) {
            $this->sp_number = $production->sp_number;
            $this->production_id = $production->id;
            $this->tbs_quantity = $production->tbs_quantity;
            $this->kg_quantity = $production->kg_quantity;
            $this->calculateTotal();
        }
        $this->sp_search = $production->sp_number;
        $this->spSuggestions = [];
        $this->showSpSuggestions = false;
        $this->calculateTax();
    }

    public function clearSpSelection()
    {
        $this->sp_number = $this->sp_search;
        $this->production_id = null;
        $this->tbs_quantity = '';
        $this->kg_quantity = '';
        $this->total_amount = 0;
        $this->spSuggestions = [];
        $this->showSpSuggestions = false;
        $this->calculateTax();
    }

    public function calculateTotal()
    {
        if ($this->kg_quantity && $this->price_per_kg) {
            $this->total_amount = $this->kg_quantity * $this->price_per_kg;
        } else {
            $this->total_amount = 0;
        }
    }

    public function calculateTax()
    {
        if ($this->is_taxable && $this->total_amount > 0 && $this->tax_percentage > 0) {
            $this->tax_amount = ($this->total_amount * $this->tax_percentage) / 100;
        } else {
            $this->tax_amount = 0;
        }
    }

    public function saveSales()
    {
        $validated = $this->validate();
        
        // Convert date from DD-MM-YYYY to YYYY-MM-DD format for database storage
        $dateForDb = \DateTime::createFromFormat('d-m-Y', $this->sale_date)->format('Y-m-d');
        
        // Handle file upload
        $proofPath = null;
        if ($this->sales_proof) {
            $proofPath = $this->sales_proof->store('sales_proofs', 'public');
        }

        SaleModel::create([
            'sp_number' => $this->sp_number, // Keep for backward compatibility
            'production_id' => $this->production_id,
            'tbs_quantity' => $this->tbs_quantity,
            'kg_quantity' => $this->kg_quantity,
            'price_per_kg' => $this->price_per_kg,
            'total_amount' => $this->total_amount,
            'sales_proof_path' => $proofPath,
            'sale_date' => $dateForDb,
            'customer_name' => $this->customer_name,
            'customer_address' => $this->customer_address,
            'is_taxable' => $this->is_taxable,
            'tax_percentage' => $this->is_taxable ? $this->tax_percentage : 0,
            'tax_amount' => $this->tax_amount,
        ]);

        // Reset form
        $this->resetForm();
        
        $this->setPersistentMessage('Sales record created successfully.', 'success');
    }

    public function resetForm()
    {
        $this->sp_number = ''; // Keep for backward compatibility
        $this->production_id = '';
        $this->tbs_quantity = '';
        $this->kg_quantity = '';
        $this->price_per_kg = '';
        $this->total_amount = 0;
        $this->sales_proof = null;
        $this->sale_date = '';
        $this->customer_name = '';
        $this->customer_address = '';
        $this->is_taxable = false;
        $this->tax_percentage = 11.00;
        $this->tax_amount = 0;
        
        // Reset autocomplete properties
        $this->sp_search = '';
        $this->spSuggestions = [];
        $this->showSpSuggestions = false;
    }

    public function filterSales()
    {
        $query = SaleModel::orderBy('sale_date', 'desc');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('sp_number', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_address', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->dateFilter) {
            $query->whereYear('sale_date', '=', substr($this->dateFilter, 0, 4))
                  ->whereMonth('sale_date', '=', substr($this->dateFilter, 5, 2));
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
                $query->whereDate('sale_date', $now->toDateString());
                break;
            case 'yesterday':
                $yesterday = $now->subDay();
                $query->whereDate('sale_date', $yesterday->toDateString());
                break;
            case 'this_week':
                $query->whereBetween('sale_date', [
                    $now->startOfWeek()->toDateString(),
                    $now->endOfWeek()->toDateString()
                ]);
                break;
            case 'last_week':
                $lastWeekStart = $now->subWeek()->startOfWeek();
                $lastWeekEnd = $now->subWeek()->endOfWeek();
                $query->whereBetween('sale_date', [
                    $lastWeekStart->toDateString(),
                    $lastWeekEnd->toDateString()
                ]);
                break;
            case 'this_month':
                $query->whereYear('sale_date', $now->year)
                      ->whereMonth('sale_date', $now->month);
                break;
            case 'last_month':
                $lastMonth = $now->subMonth();
                $query->whereYear('sale_date', $lastMonth->year)
                      ->whereMonth('sale_date', $lastMonth->month);
                break;
            case 'custom':
                if ($this->startDate && $this->endDate) {
                    $query->whereBetween('sale_date', [$this->startDate, $this->endDate]);
                }
                break;
            case 'all':
            default:
                // No additional filtering for 'all' option
                break;
        }
        
        return $query;
    }

    public function getTotalKg()
    {
        $query = SaleModel::query();
        $query = $this->applyMetricFilter($query);
        return $query->sum('kg_quantity');
    }

    public function getTotalSales()
    {
        $query = SaleModel::query();
        $query = $this->applyMetricFilter($query);
        return $query->sum('total_amount');
    }

    public function deleteSales($id)
    {
        $sale = SaleModel::find($id);
        if ($sale) {
            // Delete the proof if it exists
            if ($sale->sales_proof_path) {
                Storage::disk('public')->delete($sale->sales_proof_path);
            }
            $sale->delete();
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
        $sale = SaleModel::find($id);
        if ($sale) {
            $this->editingId = $sale->id;
            $this->sp_number = $sale->sp_number;
            $this->production_id = $sale->production_id;
            $this->tbs_quantity = $sale->tbs_quantity;
            $this->kg_quantity = $sale->kg_quantity;
            $this->price_per_kg = $sale->price_per_kg;
            $this->total_amount = $sale->total_amount;
            $this->sale_date = $sale->sale_date->format('d-m-Y'); // Format for DD-MM-YYYY display
            $this->customer_name = $sale->customer_name;
            $this->customer_address = $sale->customer_address;
            $this->is_taxable = $sale->is_taxable ?? false;
            $this->tax_percentage = $sale->tax_percentage ?? 11.00;
            $this->tax_amount = $sale->tax_amount ?? 0;
            $this->sales_proof = null; // We don't load the file, just the path
            
            // Set autocomplete search value
            $this->sp_search = $sale->sp_number;
            $this->spSuggestions = [];
            $this->showSpSuggestions = false;
            
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

    public function confirmDelete($id, $sp_number)
    {
        $this->deletingSaleId = $id;
        $this->deletingSaleName = $sp_number;
        $this->showDeleteConfirmation = true;
    }

    public function closeDeleteConfirmation()
    {
        $this->showDeleteConfirmation = false;
        $this->deletingSaleId = null;
        $this->deletingSaleName = '';
    }

    public function deleteSalesConfirmed()
    {
        $sale = SaleModel::find($this->deletingSaleId);
        if ($sale) {
            // Delete the proof if it exists
            if ($sale->sales_proof_path) {
                Storage::disk('public')->delete($sale->sales_proof_path);
            }
            $sale->delete();
            $this->setPersistentMessage('Sales record deleted successfully.', 'success');
        }
        
        $this->closeDeleteConfirmation();
    }

    public function saveSalesModal()
    {
        if ($this->isEditing) {
            $this->updateSale();
        } else {
            $this->saveSales();
        }
        
        $this->closeCreateModal();
    }

    public function updateSale()
    {
        $validated = $this->validate();
        
        // Convert date from DD-MM-YYYY to YYYY-MM-DD format for database storage
        $dateForDb = \DateTime::createFromFormat('d-m-Y', $this->sale_date)->format('Y-m-d');
        
        $sale = SaleModel::find($this->editingId);
        if ($sale) {
            // Handle file upload
            $proofPath = $sale->sales_proof_path; // Keep existing path if no new file
            if ($this->sales_proof) {
                // Delete old proof if exists
                if ($sale->sales_proof_path) {
                    Storage::disk('public')->delete($sale->sales_proof_path);
                }
                $proofPath = $this->sales_proof->store('sales_proofs', 'public');
            }

            $sale->update([
                'sp_number' => $this->sp_number,
                'tbs_quantity' => $this->tbs_quantity,
                'kg_quantity' => $this->kg_quantity,
                'price_per_kg' => $this->price_per_kg,
                'total_amount' => $this->total_amount,
                'sale_date' => $dateForDb,
                'customer_name' => $this->customer_name,
                'customer_address' => $this->customer_address,
                'sales_proof_path' => $proofPath,
                'is_taxable' => $this->is_taxable,
                'tax_percentage' => $this->is_taxable ? $this->tax_percentage : 0,
                'tax_amount' => $this->tax_amount,
            ]);

            $this->setPersistentMessage('Sales record updated successfully.', 'success');
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
    
    public function importSales()
    {
        $this->validate();
        
        try {
            $import = new SalesImport();
            Excel::import($import, $this->importFile);
            
            $this->setPersistentMessage('Sales data imported successfully.', 'success');
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
        // Updated to match current table structure with foreign keys
        $sampleData = [
            ['sp_number', 'tbs_quantity', 'kg_quantity', 'price_per_kg', 'total_amount', 'sale_date', 'customer_name', 'customer_address'],
            ['SP001', '1000.50', '950.20', '2500.00', '2375475.00', now()->format('Y-m-d'), 'PT. ABC Perkasa', 'Jl. Raya No. 123, Jakarta'],
            ['SP002', '1200.75', '1140.80', '2600.00', '2966080.00', now()->format('Y-m-d'), 'CV. XYZ Makmur', 'Jl. Merdeka No. 45, Bandung'],
            ['SP003', '950.25', '902.75', '2450.00', '2211737.50', now()->format('Y-m-d'), 'PT. Kertas Kita', 'Jl. Diponegoro No. 78, Semarang'],
        ];
        
        $csv = '';
        foreach ($sampleData as $row) {
            $csv .= '"' . implode('","', $row) . "\"\n";
        }
        
        // Save to a temporary file
        $filename = 'sample_sales_data.csv';
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
        $export = new SalesExportWithHeaders($this->exportStartDate, $this->exportEndDate);
        
        $filename = 'sales_data_export_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download($export, $filename);
    }
    
    public function exportToPdf()
    {
        // Redirect to the dedicated PDF export controller route
        return redirect()->route('sales.export.pdf', [
            'start_date' => $this->exportStartDate,
            'end_date' => $this->exportEndDate,
        ]);
    }
}
