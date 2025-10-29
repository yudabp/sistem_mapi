# Export/Import Task List for /keuangan-perusahaan and /buku-kas-kebun

## Current State Analysis:

1. **Keuangan Perusahaan** (`/keuangan-perusahaan`):
   - ✅ Export functionality already exists (`KeuanganPerusahaanExportWithHeaders.php`)
   - ❌ Import functionality missing
   - ❌ UI controls for export/import missing from component

2. **Buku Kas Kebun** (`/buku-kas-kebun`):
   - ✅ Export functionality already exists (`BukuKasKebunExportWithHeaders.php`)
   - ❌ Import functionality missing
   - ❌ UI controls for export/import missing from component

## Tasks for Keuangan Perusahaan Export/Import Implementation:

### 1. Create Import Functionality
- [ ] Create `app/Imports/KeuanganPerusahaanImport.php`
- [ ] Implement proper validation rules for import
- [ ] Handle date formatting (convert from DD-MM-YYYY to Y-m-d)
- [ ] Handle error handling for invalid data

### 2. Add Import/Export Methods to KeuanganPerusahaanComponent
- [ ] Add import-related properties (importFile, showImportModal, export dates)
- [ ] Add `openImportModal()` and `closeImportModal()` methods
- [ ] Add `importTransaction()` method with proper validation and error handling
- [ ] Add `downloadSampleExcel()` method
- [ ] Add `exportToExcel()` method
- [ ] Add `exportToPdf()` method
- [ ] Update validation rules to include importFile validation

### 3. Update KeuanganPerusahaan View
- [ ] Add export/import buttons to the header section
- [ ] Add export/import controls to search/filter section
- [ ] Add import modal dialog
- [ ] Update form validation and error handling

### 4. Create Buku Kas Kebun Import Functionality
- [ ] Create `app/Imports/BukuKasKebunImport.php`
- [ ] Implement proper validation rules for import
- [ ] Handle date formatting (convert from DD-MM-YYYY to Y-m-d)
- [ ] Handle error handling for invalid data
- [ ] Handle relationship mappings (KP ID, Debt ID, etc.)

### 5. Add Import/Export Methods to BukuKasKebunComponent
- [ ] Add import-related properties (importFile, showImportModal, export dates)
- [ ] Add `openImportModal()` and `closeImportModal()` methods
- [ ] Add `importTransaction()` method with proper validation and error handling
- [ ] Add `downloadSampleExcel()` method
- [ ] Add `exportToExcel()` method
- [ ] Add `exportToPdf()` method
- [ ] Update validation rules to include importFile validation

### 6. Update BukuKasKebun View
- [ ] Add export/import buttons to the header section
- [ ] Add export/import controls to search/filter section
- [ ] Add import modal dialog
- [ ] Update form validation and error handling

## Specific Implementation Notes:

### For KeuanganPerusahaanImport.php:
- Map columns: ID, Transaction Number, Transaction Date, Transaction Type, Amount, Source/Destination, Received By, Notes, Category
- Transaction type should be validated as 'income' or 'expense'
- Amount should be numeric
- Date should follow proper format
- Transaction number can be auto-generated if empty

### For BukuKasKebunImport.php:
- Map columns: ID, Transaction Number, Transaction Date, Transaction Type, Amount, Source/Destination, Received By, Notes, Category, Expense Category, Debt ID, KP ID
- Handle relationship lookups for Debt ID and KP ID
- Validate transaction type as 'income' or 'expense'
- Handle date formatting

### For UI Updates:
- Add buttons: "Import Data", "Export to Excel", "Export to PDF" in header
- Add date range selectors for exports
- Add sample download functionality
- Ensure UI matches other modules' designs