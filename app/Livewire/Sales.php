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
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\View;

class Sales extends Component
{
    use WithFileUploads;

    public $sp_number;
    public $tbs_quantity;
    public $kg_quantity;
    public $price_per_kg;
    public $total_amount;
    public $sales_proof;
    public $sale_date;
    public $customer_name;
    public $customer_address;
    
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

    protected $queryString = ['search', 'dateFilter', 'metricFilter'];

    public $importFile = null;
    public $exportStartDate = null;
    public $exportEndDate = null;
    public $showImportModal = false;

    protected $rules = [
        'sp_number' => 'required',
        'kg_quantity' => 'required|numeric',
        'price_per_kg' => 'required|numeric',
        'sale_date' => 'required|date_format:d-m-Y',
        'customer_name' => 'required',
        'customer_address' => 'required',
        'sales_proof' => 'nullable|image|max:10240', // Max 10MB
        'importFile' => 'required|file|mimes:xlsx,xls,csv',
    ];

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
        if ($this->kg_quantity && $this->price_per_kg) {
            $this->total_amount = $this->kg_quantity * $this->price_per_kg;
        }
    }

    public function updatedKgQuantity()
    {
        if ($this->kg_quantity && $this->price_per_kg) {
            $this->total_amount = $this->kg_quantity * $this->price_per_kg;
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
            'sp_number' => $this->sp_number,
            'tbs_quantity' => $this->tbs_quantity,
            'kg_quantity' => $this->kg_quantity,
            'price_per_kg' => $this->price_per_kg,
            'total_amount' => $this->total_amount,
            'sales_proof_path' => $proofPath,
            'sale_date' => $dateForDb,
            'customer_name' => $this->customer_name,
            'customer_address' => $this->customer_address,
        ]);

        // Reset form
        $this->resetForm();
        
        $this->setPersistentMessage('Sales record created successfully.', 'success');
    }

    public function resetForm()
    {
        $this->sp_number = '';
        $this->tbs_quantity = '';
        $this->kg_quantity = '';
        $this->price_per_kg = '';
        $this->total_amount = '';
        $this->sales_proof = null;
        $this->sale_date = '';
        $this->customer_name = '';
        $this->customer_address = '';
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
            $this->tbs_quantity = $sale->tbs_quantity;
            $this->kg_quantity = $sale->kg_quantity;
            $this->price_per_kg = $sale->price_per_kg;
            $this->total_amount = $sale->total_amount;
            $this->sale_date = $sale->sale_date->format('d-m-Y'); // Format for DD-MM-YYYY display
            $this->customer_name = $sale->customer_name;
            $this->customer_address = $sale->customer_address;
            $this->sales_proof = null; // We don't load the file, just the path
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
            Excel::import(new SalesImport, $this->importFile);
            $this->setPersistentMessage('Sales data imported successfully.', 'success');
            $this->closeImportModal();
        } catch (\Exception $e) {
            $this->setPersistentMessage('Error importing data: ' . $e->getMessage(), 'error');
        }
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
        $exporter = new SalesPdfExporter($this->exportStartDate, $this->exportEndDate);
        
        $html = $exporter->generate();
        
        // Ensure proper UTF-8 encoding with fallback
        if (function_exists('mb_convert_encoding')) {
            $html = mb_convert_encoding($html, 'UTF-8', 'auto');
        } else {
            $html = utf8_encode($html);
        }
        
        $filename = 'sales_data_export_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        
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
