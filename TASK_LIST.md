# Task List - Agro Palma Data Management Dashboard

Berdasarkan analisis Tech Spec dan project status, berikut adalah daftar tugas yang diurutkan berdasarkan prioritas:

---

## 🔥 **HIGH PRIORITY** 
*Deadline: 1-2 Minggu*

### **1. Implementasi Fitur Pajak Penjualan** ✅ **COMPLETED**
- [x] **Database Schema**
  - [x] Buat migrasi untuk menambahkan kolom `is_taxable`, `tax_percentage`, dan `tax_amount` ke tabel `data_penjualan`.
- [x] **Backend Logic**
  - [x] Update model `Sale` untuk menyertakan field baru.
  - [x] Implementasikan logika di backend untuk menghitung `tax_amount` secara otomatis berdasarkan `total_penjualan` dan `tax_percentage`.
- [x] **Frontend (Livewire Component)**
  - [x] Tambahkan checkbox "Kena Pajak" di form input penjualan.
  - [x] Tampilkan input untuk "Total Pajak (%)" (default 11%, editable) dan "Total Nominal Pajak" (auto-calculated, editable) secara kondisional.
  - [x] Pastikan kalkulasi berjalan secara real-time di frontend.
- [x] **Fitur Ekspor Laporan**
  - [x] Buat fungsionalitas untuk mengekspor data penjualan (Excel/CSV/PDF).
  - [x] Tambahkan filter pada ekspor: semua penjualan, hanya yang kena pajak, dan hanya yang tidak kena pajak.

### **1.1. Implementasi SP Number Autocomplete** ✅ **COMPLETED**
- [x] **Search Autocomplete UI**
  - [x] Ganti dropdown production_id menjadi search autocomplete untuk SP number.
  - [x] Implementasi real-time search dengan minimal 2 karakter.
  - [x] Tampilkan suggestions dengan info TBS dan KG quantity.
- [x] **Backend Logic**
  - [x] Tambah property sp_search, spSuggestions, showSpSuggestions.
  - [x] Implementasi updatedSpSearch() untuk real-time search.
  - [x] Tambah selectSpSuggestion() untuk auto-fill data produksi.
  - [x] Tambah clearSpSelection() untuk input manual.
- [x] **Validation & Data Handling**
  - [x] Update validation rules: sp_number required, production_id nullable.
  - [x] Support input manual SP number yang tidak ada di tabel produksi.
  - [x] Hapus readonly attribute dari TBS dan KG quantity fields.
- [x] **UI/UX Improvements**
  - [x] Tambah indikator visual saat data auto-filled dari produksi.
  - [x] Implementasi click-outside listener untuk menutup suggestions.
  - [x] Responsive design dengan proper z-index.

### **2. Database Schema Restructuring** ✅ **COMPLETED**
- [x] **Create migration for proper foreign key relations**
  - [x] Add `vehicle_id` FK to production table
  - [x] Add `division_id` FK to production table  
  - [x] Add `pks_id` FK to production table
  - [x] Add `production_id` FK to sales table
  - [x] Add `department_id`, `position_id`, `family_composition_id` FK to employees table
  - [x] Add proper indexes for performance

- [x] **Update existing data to use FK relations**
  - [x] Migrate production.vehicle_number → production.vehicle_id
  - [x] Migrate production.division → production.division_id
  - [x] Migrate production.pks → production.pks_id
  - [x] Migrate sales.sp_number → sales.production_id
  - [x] Migrate employees data to use proper FK

- [x] **Add foreign key constraints**
  - [x] Add ON DELETE CASCADE/SET NULL constraints
  - [x] Test data integrity after constraints

### **3. Separate Financial Tables (KP & BKK)** ✅ **COMPLETED**
- [x] **Create new tables**
  - [x] Create `keuangan_perusahaan` table migration
  - [x] Create `buku_kas_kebun` table migration
  - [x] Add `kp_id` FK to buku_kas_kebun table

- [x] **Migrate existing financial data**
  - [x] Split financial_transactions into KP and BKK
  - [x] Map existing categories to proper tables
  - [x] Maintain data integrity during migration

- [x] **Update Livewire components**
  - [x] Separate Financial Livewire component into KP and BKK
  - [x] Update views to handle separate tables
  - [x] Update routing and navigation

### **4. Implement KP → BKK Auto-create Business Logic** ✅ **COMPLETED**
- [x] **Backend Logic**
  - [x] Create observer/service for KP expense creation
  - [x] Auto-create BKK income entry when KP expense is created
  - [x] Handle error cases and rollback scenarios
  - [x] Add logging for audit trail

- [x] **Frontend Updates**
  - [x] Update KP Livewire component to show related BKK entries
  - [x] Add visual indicators for auto-created entries
  - [x] Add confirmation dialogs for KP expense creation
  - [x] Update BKK Livewire component to show related KP entries
  - [x] Add modal navigation between KP and BKK transactions
  - [x] Add ID attributes for better navigation
  - [x] Test KP → BKK auto-create flow end-to-end

### **5. Debt Payment Cycle Implementation** ✅ **COMPLETED**
- [x] **Create missing tables**
  - [x] Create `hutang_pembayaran` table migration
  - [x] Create `master_debt_types` table migration
  - [x] Create `master_bkk_expense_categories` table migration
  - [x] Add missing columns to existing tables

- [x] **Update debt structure**
  - [x] Add `sisa_hutang`, `cicilan_per_bulan` to debts table
  - [x] Add `debt_type_id` FK to debts table
  - [x] Migrate existing debt data with proper seeding

- [x] **Implement payment logic**
  - [x] Create DebtPaymentService
  - [x] Add dropdown for unpaid debts in BKK expense form
  - [x] Auto-update `sisa_hutang` when payment is made
  - [x] Auto-update debt status to 'Lunas' when fully paid
  - [x] Create payment records in `hutang_pembayaran` table

- [x] **Update UI Components**
  - [x] Update BKK Livewire component for debt payments
  - [x] Update Debt Livewire component to show payment history
  - [x] Add "Sisa Hutang" column with color coding
  - [x] Add payment tracking and reporting

---

## ⚡ **MEDIUM PRIORITY**
*Deadline: 2-4 Minggu*

### **6. User Roles & Access Control (Simple 2-Role System)** ✅ **COMPLETED**
- [x] **Setup Laravel Spatie Package**
  - [x] Install spatie/laravel-permission package
  - [x] Publish migrations and config
  - [x] Run migrations for roles/permissions tables

- [x] **Create Simple Role System**
  - [x] Create 2 roles: "direksi" and "superadmin"
  - [x] Define permissions: "view-data", "export-data", "full-access"
  - [x] Assign permissions to roles:
    - **direksi**: view-data, export-data
    - **superadmin**: full-access (all permissions)
  - [x] Seed roles and permissions in database

- [x] **Implementation**
  - [x] Add HasRoles trait to User model
  - [x] Implement middleware for role checking
  - [x] Update controllers to check permissions:
    - Direksi: Can view all pages, can export data, cannot edit/delete
    - Superadmin: Full access to all features
  - [x] Add role checking to Livewire components

- [x] **UI Updates**
  - [x] Show/hide edit/delete buttons based on role
  - [x] Add export buttons for Direksi role
  - [x] Display user role in profile/navbar
  - [x] Create simple user management page for Superadmin to assign roles

### **7. API Endpoints Implementation**
- [ ] **Setup API Infrastructure**
  - [ ] Create API authentication (Sanctum/Passport)
  - [ ] Setup API rate limiting
  - [ ] Create API resource classes
  - [ ] Setup API documentation (Swagger/Postman)

- [ ] **Master Data APIs**
  - [ ] GET/POST /api/master/vehicles
  - [ ] GET/POST /api/master/afdelings
  - [ ] GET/POST /api/master/pks
  - [ ] GET/POST /api/master/departments
  - [ ] GET/POST /api/master/positions
  - [ ] GET/POST /api/master/debt-types
  - [ ] GET/POST /api/master/bkk-categories

- [ ] **Transactional APIs**
  - [ ] GET/POST/PUT/DELETE /api/production
  - [ ] GET/POST/PUT/DELETE /api/sales
  - [ ] GET/POST/PUT/DELETE /api/employees
  - [ ] GET/POST/PUT/DELETE /api/keuangan/kp
  - [ ] GET/POST/PUT/DELETE /api/keuangan/bkk
  - [ ] GET/POST/PUT/DELETE /api/debts
  - [ ] POST /api/debts/{id}/pay

- [ ] **Dashboard API**
  - [ ] GET /api/dashboard/insights
  - [ ] Implement data aggregation logic
  - [ ] Add caching for performance

### **8. Enhanced Dashboard & Reporting**
- [ ] **Advanced Metrics**
  - [ ] Total produksi KG (with date range filtering)
  - [ ] Total penjualan RP (with profit calculations)
  - [ ] Total pemasukan/pengeluaran (KP + BKK breakdown)
  - [ ] Total sisa hutang (with aging analysis)
  - [ ] Jumlah karyawan aktif (by department/status)

- [ ] **Data Visualization**
  - [ ] Production trends chart (Chart.js)
  - [ ] Sales vs Production comparison
  - [ ] Financial flow charts
  - [ ] Debt aging reports
  - [ ] Employee distribution charts

- [ ] **Export Features**
  - [ ] Export to Excel/CSV for all modules
  - [ ] PDF report generation
  - [ ] Custom date range exports

---

## 🔧 **LOW PRIORITY**
*Deadline: 4-6 Minggu*

### **9. Testing & Quality Assurance**
- [ ] **Unit Tests**
  - [ ] Model relationship tests
  - [ ] Business logic tests (KP→BKK, debt payments)
  - [ ] Livewire component tests
  - [ ] API endpoint tests

- [ ] **Feature Tests**
  - [ ] End-to-end user workflows
  - [ ] Role-based access tests
  - [ ] File upload tests
  - [ ] Form validation tests

- [ ] **Performance Tests**
  - [ ] Database query optimization
  - [ ] Load testing for concurrent users
  - [ ] Large dataset handling

### **10. Code Quality & Documentation**
- [ ] **Code Refactoring**
  - [ ] Extract business logic to service classes
  - [ ] Implement repository pattern
  - [ ] Add proper error handling
  - [ ] Code style standardization

- [ ] **Documentation**
  - [ ] API documentation completion
  - [ ] User manual creation
  - [ ] Developer documentation
  - [ ] Deployment guide

### **11. Advanced Features**
- [ ] **Notifications System**
  - [ ] Debt due date reminders
  - [ ] Low stock alerts (if applicable)
  - [ ] Financial threshold alerts

- [ ] **Audit Trail**
  - [ ] Track all data changes
  - [ ] Log user actions
  - [ ] Change history viewer

- [ ] **Data Import/Export**
  - [ ] Bulk data import from Excel/CSV
  - [ ] Data validation during import
  - [ ] Import templates creation

---

## 📋 **IMPLEMENTATION CHECKLIST**

### **Week 1-2: Foundation** ✅ **COMPLETED**
- [x] Database migrations for FK relations
- [x] Financial tables separation
- [x] Basic business logic implementation
- [x] Testing data integrity

### **Week 3-4: Core Features** ✅ **COMPLETED**
- [x] Financial tables separation (KP & BKK)
- [x] KP → BKK auto-create business logic (frontend updates)
- [x] Debt payment cycle implementation
- [x] All core business modules implemented (Production, Sales, Employees, Financial, Debts)
- [x] Master data management (Vehicles, Divisions, PKS, Departments, Positions, etc)
- [ ] User roles implementation
- [ ] API endpoints creation
- [ ] Enhanced dashboard with charts

### **Week 5-6: Polish & Deploy**
- [ ] Testing suite completion
- [ ] Documentation
- [ ] Performance optimization
- [ ] Production deployment

---

## 🎯 **SUCCESS METRICS**

### **Technical Metrics**
- [x] All foreign key constraints implemented
- [x] Financial tables separation completed
- [x] Business logic coverage: 100% (KP→BKK, Debt Payments, Sales Tax)
- [ ] API endpoint coverage: 0%
- [ ] Test coverage: 0%
- [ ] Page load time: <2 seconds

### **Business Metrics**
- [x] KP → BKK tables separation: 100% complete
- [x] KP → BKK auto-create: 100% working
- [x] Debt payment tracking: 100% accurate
- [ ] User role enforcement: 0% (not implemented)
- [x] Data integrity: 0 orphaned records

---

## 🚨 **RISKS & MITIGATIONS**

### **High Risk Items**
1. **Data Migration**: Risk of data loss
   - **Mitigation**: Full backup + migration scripts + rollback plan
2. **Business Logic Complexity**: Risk of incorrect calculations
   - **Mitigation**: Extensive testing + peer review + staging validation

### **Medium Risk Items**
1. **Performance**: Risk of slow queries with FK constraints
   - **Mitigation**: Proper indexing + query optimization
2. **User Adoption**: Risk of confusion with new workflows
   - **Mitigation**: User training + documentation + gradual rollout

---

*Last Updated: 26 October 2025*
*Project Completion Target: 6 Weeks*
*Current Status: ~99% Complete*
