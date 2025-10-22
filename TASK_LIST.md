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
  - [x] Buat fungsionalitas untuk mengekspor data penjualan (misalnya ke Excel/CSV).
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

### **3. Separate Financial Tables (KP & BKK)**
- [ ] **Create new tables**
  - [ ] Create `keuangan_perusahaan` table migration
  - [ ] Create `buku_kas_kebun` table migration
  - [ ] Add `kp_id` FK to buku_kas_kebun table

- [ ] **Migrate existing financial data**
  - [ ] Split financial_transactions into KP and BKK
  - [ ] Map existing categories to proper tables
  - [ ] Maintain data integrity during migration

- [ ] **Update Livewire components**
  - [ ] Separate Financial Livewire component into KP and BKK
  - [ ] Update views to handle separate tables
  - [ ] Update routing and navigation

### **4. Implement KP → BKK Auto-create Business Logic**
- [ ] **Backend Logic**
  - [ ] Create observer/service for KP expense creation
  - [ ] Auto-create BKK income entry when KP expense is created
  - [ ] Handle error cases and rollback scenarios
  - [ ] Add logging for audit trail

- [ ] **Frontend Updates**
  - [ ] Update KP Livewire component to show related BKK entries
  - [ ] Add visual indicators for auto-created entries
  - [ ] Add confirmation dialogs for KP expense creation

### **5. Debt Payment Cycle Implementation**
- [ ] **Create missing tables**
  - [ ] Create `hutang_pembayaran` table migration
  - [ ] Create `master_debt_types` table migration
  - [ ] Create `master_bkk_expense_categories` table migration

- [ ] **Update debt structure**
  - [ ] Add `sisa_hutang`, `cicilan_per_bulan` to debts table
  - [ ] Add `debt_type_id` FK to debts table
  - [ ] Migrate existing debt data

- [ ] **Implement payment logic**
  - [ ] Create debt payment service
  - [ ] Add dropdown for unpaid debts in BKK expense form
  - [ ] Auto-update `sisa_hutang` when payment is made
  - [ ] Auto-update debt status to 'Lunas' when fully paid
  - [ ] Create payment records in `hutang_pembayaran` table

- [ ] **Update UI Components**
  - [ ] Update BKK Livewire component for debt payments
  - [ ] Update Debt Livewire component to show payment history
  - [ ] Add payment tracking and reporting

---

## ⚡ **MEDIUM PRIORITY**
*Deadline: 2-4 Minggu*

### **6. User Roles & Access Control**
- [ ] **Database Setup**
  - [ ] Create `roles` table (Direksi, Superadmin)
  - [ ] Create `permissions` table
  - [ ] Create `role_permissions` pivot table
  - [ ] Add `role_id` to users table

- [ ] **Authentication & Authorization**
  - [ ] Implement role-based middleware
  - [ ] Create policies for each module
  - [ ] Add role checking to Livewire components
  - [ ] Update login to handle roles

- [ ] **UI Updates**
  - [ ] Hide/show menu items based on role
  - [ ] Disable edit/delete for Direksi role
  - [ ] Add role indicators in UI
  - [ ] Create role management interface for Superadmin

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

### **Week 1-2: Foundation**
- [ ] Database migrations for FK relations
- [ ] Financial tables separation
- [ ] Basic business logic implementation
- [ ] Testing data integrity

### **Week 3-4: Core Features**
- [ ] Debt payment cycle
- [ ] User roles implementation
- [ ] API endpoints creation
- [ ] Enhanced dashboard

### **Week 5-6: Polish & Deploy**
- [ ] Testing suite completion
- [ ] Documentation
- [ ] Performance optimization
- [ ] Production deployment

---

## 🎯 **SUCCESS METRICS**

### **Technical Metrics**
- [ ] All foreign key constraints implemented
- [ ] Business logic coverage: 100%
- [ ] API endpoint coverage: 100%
- [ ] Test coverage: >80%
- [ ] Page load time: <2 seconds

### **Business Metrics**
- [ ] KP → BKK auto-create: 100% working
- [ ] Debt payment tracking: 100% accurate
- [ ] User role enforcement: 100% compliant
- [ ] Data integrity: 0 orphaned records

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

*Last Updated: 20 October 2025*
*Project Completion Target: 6 Weeks*
*Current Status: ~75% Complete*