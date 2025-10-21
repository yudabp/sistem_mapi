Tentu, saya akan menganalisis semua gambar yang Anda berikan dan memperbarui *Technical Specification* (Tech Spec) secara komprehensif.

Gambar-gambar tersebut berisi *flowchart* dan diagram alur data yang sangat penting, memberikan detail yang tidak ada dalam teks PRD awal. Ini memungkinkan kita untuk mendefinisikan skema database dan logika bisnis dengan lebih akurat.

---

### **Technical Specification (Versi 2.0): Agro Palma Data Management Dashboard**

Dokumen ini adalah versi terbaru yang mengintegrasikan detail visual dari alur data yang diberikan.

## 1. Ikhtisar Teknis (Tidak Berubah)

**Tujuan Proyek:** Membangun aplikasi *dashboard web responsive* untuk sentralisasi, manajemen (CRUD), dan visualisasi data perusahaan (Produksi, Penjualan, Keuangan, Karyawan) secara *real-time*.

**Target Pengguna & Peran (Roles):**
1.  **Direksi (Read-only)**
2.  **Superadmin (Full Access)**

**Rekomendasi Tumpukan Teknologi (Technology Stack):**
* **Frontend:** React.js (Next.js) atau Vue.js (Nuxt.js).
* **Backend:** Node.js (Express/NestJS) atau Laravel (PHP).
* **Database:** PostgreSQL atau MySQL.
* **Visualisasi Data:** Chart.js, D3.js, atau ECharts.
* **Penyimpanan File:** Dibutuhkan solusi penyimpanan objek seperti AWS S3, Google Cloud Storage, atau penyimpanan lokal di server untuk menangani unggahan foto/bukti.

---

## 2. Model Data (Skema Database) - Diperbarui

Berdasarkan *flowchart*, skema database diperbarui secara signifikan untuk mencakup relasi dan data master.

#### Tabel Master / Lookup (Data Pilihan)
Tabel-tabel ini penting untuk fitur "jika tidak ada bisa tambah".

* **`master_vehicles`**: Menyimpan Nomor Polisi (`no_pol`).
* **`master_afdelings`**: Menyimpan data Afdeling.
* **`master_pks`**: Menyimpan data PKS (Pabrik Kelapa Sawit).
* **`master_employee_departments`**: Menyimpan data Bagian Karyawan (Staff, Pegawai, dll).
* **`master_employee_positions`**: Menyimpan data Jabatan (Direktur, Manager, dll).
* **`master_employee_groups`**: Menyimpan data Golongan/Susunan Keluarga.
* **`master_debt_types`**: Menyimpan data perihal hutang (Susu Tunggakan, Investor, dll).
* **`master_bkk_expense_categories`**: Menyimpan Kategori Pengeluaran BKK (Gaji, Hutang, Operasional).

#### Tabel Transaksional Utama

1.  **`users`** dan **`roles`** (Tidak berubah)

2.  **`data_produksi` (Diperbarui)**
    * `id` (PK)
    * `no_sp` (string, **unique**, key untuk relasi ke penjualan)
    * `tanggal` (date)
    * `vehicle_id` (FK ke `master_vehicles.id`)
    * `jumlah_tbs` (integer)
    * `jumlah_kg` (decimal)
    * `afdeling_id` (FK ke `master_afdelings.id`)
    * `pks_id` (FK ke `master_pks.id`)
    * `foto_urls` (JSON atau TEXT, untuk menyimpan array path file)
    * `created_by_user_id` (FK ke `users.id`)
    * `created_at`, `updated_at`

3.  **`data_penjualan` (Diperbarui)**
    * `id` (PK)
    * `produksi_id` (FK ke `data_produksi.id`, **relasi kunci via No. SP**)
    * `tanggal_jual` (date)
    * `harga_jual_per_kg` (decimal)
    * `total_penjualan` (decimal, **dihitung otomatis**)
    * `is_taxable` (boolean, default: false)
    * `tax_percentage` (decimal, nullable, default: 11)
    * `tax_amount` (decimal, nullable)
    * `created_by_user_id` (FK ke `users.id`)
    * `created_at`, `updated_at`

4.  **`data_karyawan` (Diperbarui)**
    * `id` (PK)
    * `ndp` (string, unique)
    * `nama` (string)
    * `department_id` (FK ke `master_employee_departments.id`)
    * `position_id` (FK ke `master_employee_positions.id`)
    * `group_id` (FK ke `master_employee_groups.id`)
    * `gaji_bulanan` (decimal)
    * `status` (string, enum: 'Aktif', 'Tidak Aktif', 'A.IM')
    * `created_at`, `updated_at`

5.  **`keuangan_perusahaan (KP)` (Diperbarui)**
    * `id` (PK)
    * `tanggal` (date)
    * `tipe` (enum: 'Pemasukan', 'Pengeluaran')
    * `deskripsi` (string, mis: 'Pemasukan Dari X' atau 'Pengeluaran Untuk Y')
    * `penerima` (string)
    * `jumlah` (decimal)
    * `bukti_url` (string, path ke file gambar)
    * `catatan` (text)
    * `created_by_user_id` (FK ke `users.id`)
    * `created_at`, `updated_at`

6.  **`buku_kas_kebun (BKK)` (Diperbarui)**
    * `id` (PK)
    * `tanggal` (date)
    * `tipe` (enum: 'Pemasukan', 'Pengeluaran')
    * `deskripsi` (string)
    * `penerima` (string, untuk Pemasukan)
    * `expense_category_id` (FK ke `master_bkk_expense_categories.id`, untuk Pengeluaran)
    * `jumlah` (decimal)
    * `bukti_url` (string)
    * `catatan` (text)
    * `kp_id` (FK ke `keuangan_perusahaan.id`, nullable)
    * `created_by_user_id` (FK ke `users.id`)
    * `created_at`, `updated_at`

7.  **`data_hutang (HT)` (Diperbarui)**
    * `id` (PK)
    * `tanggal_hutang` (date)
    * `kreditor` (string, 'Hutang Kepada')
    * `debt_type_id` (FK ke `master_debt_types.id`, 'Utang Perihal')
    * `jumlah_hutang` (decimal)
    * `sisa_hutang` (decimal, **diupdate oleh pembayaran**)
    * `cicilan_per_bulan` (decimal)
    * `status` (enum: 'Belum Lunas', 'Lunas')
    * `created_by_user_id` (FK ke `users.id`)
    * `created_at`, `updated_at`

8.  **`hutang_pembayaran` (Tabel Baru)**
    * `id` (PK)
    * `hutang_id` (FK ke `data_hutang.id`)
    * `bkk_id` (FK ke `buku_kas_kebun.id`, sebagai bukti pembayaran)
    * `tanggal_bayar` (date)
    * `jumlah_bayar` (decimal)

---

## 3. Logika Bisnis Kunci (Wajib Diimplementasikan) - Diperbarui

1.  **Alur Pembuatan Data Penjualan (BARU):**
    * **Trigger:** User memulai input data penjualan.
    * **Aksi:**
        1.  UI menyediakan input untuk `No. SP`.
        2.  Setelah `No. SP` dimasukkan, sistem melakukan *lookup* ke tabel `data_produksi`.
        3.  Jika ditemukan, data terkait (seperti `Jumlah KG`, `Tanggal Produksi`, dll.) ditampilkan di form dan bersifat *read-only*.
        4.  User menginput `Harga Jual / kg`.
        5.  `Total Penjualan` dihitung secara otomatis (`Harga Jual / kg` * `jumlah_kg` dari data produksi) dan ditampilkan.
        6.  Saat disimpan, `produksi_id` dan data lainnya disimpan ke tabel `data_penjualan`.

2.  **Alur Keterkaitan KP ke BKK (Tetap):**
    * Saat Superadmin membuat entri `Pengeluaran` di KP, sistem secara otomatis membuat entri `Pemasukan` di BKK.

3.  **Alur Pelunasan Hutang melalui BKK (Diperbarui & Lebih Detail):**
    * **Trigger:** User membuat entri `Pengeluaran` di BKK dan memilih kategori `expense_category_id` yang merujuk pada "Hutang".
    * **Aksi:**
        1.  UI akan menampilkan *dropdown* atau *searchable list* untuk memilih hutang yang masih berstatus 'Belum Lunas' dari tabel `data_hutang`.
        2.  User memasukkan jumlah yang dibayarkan.
        3.  Saat disimpan, backend akan:
            a. Membuat entri di BKK.
            b. Membuat entri baru di tabel `hutang_pembayaran` yang menautkan pembayaran ini (via `bkk_id`) ke hutang yang relevan (via `hutang_id`).
            c. Mengurangi `sisa_hutang` di tabel `data_hutang`.
            d. Mengubah `status` hutang menjadi 'Lunas' jika `sisa_hutang` <= 0.

4.  **Manajemen Data Master (BARU):**
    * Untuk setiap data master (Kendaraan, Afdeling, PKS, dll.), UI harus menyediakan antarmuka sederhana (CRUD) agar Superadmin bisa menambahkan opsi baru sesuai kebutuhan.

---

## 4. Spesifikasi API Endpoints (Diperbarui)

#### Endpoints Data Master (BARU)
* `GET, POST /master/vehicles`
* `GET, POST /master/afdelings`
* `GET, POST /master/pks`
* (dan seterusnya untuk semua tabel master)

#### Endpoints Transaksional
* `POST /penjualan`: Body request kini cukup berisi `{ "no_sp": "...", "harga_jual_per_kg": ... }`. Backend akan menangani sisanya.
* `POST /produksi`, `POST /keuangan/kp`, `POST /keuangan/bkk`: Endpoint ini harus mendukung `multipart/form-data` untuk menangani unggahan file.
* `GET /dashboard/insights`: Endpoint ini harus diperbarui untuk memberikan data agregat seperti yang digambarkan di "Sistem Digital Map":
    * `total_produksi_kg`
    * `total_penjualan_rp`
    * `total_pemasukan` (dengan rincian dari KP dan BKK)
    * `total_pengeluaran` (dengan rincian dari KP dan BKK)
    * `total_sisa_hutang`
    * `jumlah_karyawan_aktif`

---

## 5. Rencana Sprint Teknis (2 Minggu) - Disesuaikan

### **Minggu 1: Fondasi, Master Data & Modul Dasar**
* **Backend:**
    * Setup proyek, database, dan autentikasi.
    * Implementasi skema database **lengkap** (termasuk tabel master).
    * Implementasi API CRUD untuk **semua tabel Master**.
    * Implementasi mekanisme **unggah file**.
    * Implementasi API CRUD untuk **Data Produksi** & **Data Karyawan**.
* **Frontend:**
    * Setup proyek, login, dan layout.
    * Implementasi modul UI untuk **semua Master Data**.
    * Implementasi modul UI untuk **Data Produksi** (termasuk upload foto).
    * Implementasi modul UI untuk **Data Karyawan**.

### **Minggu 2: Modul Kompleks & Finalisasi**
* **Backend:**
    * Implementasi API CRUD untuk **Data Penjualan** dengan **logika lookup No. SP**.
    * Implementasi API CRUD untuk semua modul **Keuangan (KP, BKK, HT)**.
    * Implementasi **Logika Bisnis Kunci** (KP -> BKK dan Pelunasan Hutang BKK -> HT).
    * Implementasi endpoint `/dashboard/insights`.
* **Frontend:**
    * Implementasi modul UI untuk **Data Penjualan** (dengan alur lookup).
    * Implementasi modul UI untuk **Keuangan** (KP, BKK, HT), pastikan UI mendukung alur pelunasan hutang.
    * Implementasi halaman **Dashboard (Ringkasan)** dengan visualisasi data.
* **Deployment & Testing:** Deploy ke staging dan lakukan pengujian E2E (End-to-End).

---

## 6. Kriteria Penerimaan Teknis (Ditambahkan)
* **Alur Penjualan:** Membuat entri penjualan harus berhasil menarik data KG dari produksi dan menghitung total secara akurat.
* **Alur Keuangan:** Transaksi pengeluaran dari KP harus secara otomatis tercermin sebagai pemasukan di BKK.
* **Alur Hutang:** Pembayaran hutang melalui BKK harus mengurangi sisa hutang dan mencatat riwayat pembayaran.
* **Unggah File:** Pengguna harus bisa mengunggah dan melihat kembali gambar bukti pada modul Produksi dan Keuangan.
* **Data Master:** Superadmin harus bisa menambahkan opsi baru pada form (misal: No. Polisi baru) melalui antarmuka manajemen data master.

---

## 7. Status Implementasi Fitur (Project Analysis)

### ✅ **DONE (Sesuai Spec & Berjalan)**

#### **Data Master Management**
- **Vehicle Numbers**: CRUD untuk nomor polisi kendaraan
- **Divisions**: CRUD untuk data afdeling  
- **PKS**: CRUD untuk data Pabrik Kelapa Sawit
- **Departments**: CRUD untuk bagian/departemen karyawan
- **Positions**: CRUD untuk jabatan karyawan
- **Family Compositions**: CRUD untuk susunan keluarga
- **Employment Statuses**: CRUD untuk status kerja

#### **Core Business Modules**
- **Production Management**: 
  - Input data produksi dengan semua field required
  - Upload foto SP dengan preview
  - Search & filtering berdasarkan berbagai kriteria
  - CRUD operations lengkap
- **Sales Management**:
  - Input data penjualan dengan dropdown SP Number
  - Auto-fill TBS Quantity & KG Quantity dari data produksi
  - Auto-calculation Total Amount (KG × Price per KG)
  - Real-time calculation saat Price per KG diubah
  - Field read-only untuk auto-filled data
- **Employee Management**:
  - Data karyawan lengkap dengan NDP, nama, department, position
  - Gaji bulanan dan status management
  - CRUD operations lengkap
- **Financial Transactions**:
  - Input transaksi keuangan (pemasukan/pengeluaran)
  - Upload bukti transaksi
  - Search & filtering
- **Cash Book (BKK)**:
  - Buku kas kebun dengan kategori pengeluaran
  - Upload bukti transaksi
  - CRUD operations
- **Debt Management**:
  - Input data hutang dengan kreditor dan jumlah
  - Status tracking (Belum Lunas/Lunas)
  - CRUD operations

#### **UI/UX Features**
- **Responsive Design**: Mobile-friendly dengan Tailwind CSS
- **Modal Forms**: Clean modal interfaces untuk CRUD
- **Photo Preview**: View uploaded images in modal
- **Advanced Search**: Multi-criteria search & filtering
- **Dashboard**: Basic metrics calculation (Total KG, Total Sales, etc)
- **Real-time Updates**: Livewire untuk dynamic content

---

### ⚠️ **PARTIALLY DONE (Ada tapi Belum Sesuai Spec)**

#### **Sales Module**
- **Status**: ✅ Auto-fill & calculation works
- **Issue**: ❌ Menggunakan string `sp_number` instead of proper FK relation ke production
- **Spec Requirement**: `produksi_id` (FK) dengan proper database relation
- **Current Implementation**: String lookup tanpa foreign key constraint

#### **Financial Structure**
- **Status**: ✅ Basic financial transactions work
- **Issue**: ❌ Satu tabel `financial_transactions` instead of separate `keuangan_perusahaan (KP)` dan `buku_kas_kebun (BKK)`
- **Spec Requirement**: Dua tabel terpisah dengan relasi KP → BKK
- **Current Implementation**: Single table dengan category differentiation

#### **Debt Management**
- **Status**: ✅ Basic CRUD works
- **Issue**: ❌ Tidak ada payment cycle tracking
- **Spec Requirement**: Pelunasan hutang melalui BKK dengan update `sisa_hutang`
- **Current Implementation**: Static debt data tanpa payment tracking

#### **Database Relations**
- **Status**: ✅ **COMPLETED** - All proper FK relations implemented
- **Implementation**: ✅ Foreign key constraints added with proper indexes
- **Data Migration**: ✅ Existing data migrated to use FK relations
- **Backward Compatibility**: ✅ String fields kept for fallback
- **Models**: ✅ All models updated with Eloquent relationships

---

### ❌ **NOT DONE (Belum Ada Sama Sekali)**

#### **Business Logic Implementation**
- **KP → BKK Auto-create**: Saat membuat pengeluaran di KP, otomatis create pemasukan di BKK
- **Debt Payment Cycle**: Pembayaran hutang melalui BKK dengan:
  - Dropdown pilih hutang yang belum lunas
  - Update otomatis `sisa_hutang`
  - Create record di `hutang_pembayaran` table
  - Update status menjadi 'Lunas' jika lunas

#### **Missing Database Tables**
- `hutang_pembayaran`: Tracking pembayaran hutang
- `master_debt_types`: Kategori hutang (Susu Tunggakan, Investor, etc)
- `master_bkk_expense_categories`: Kategori pengeluaran BKK (Gaji, Hutang, Operasional)

#### **Advanced Features**
- **User Roles Management**: Direksi (read-only) vs Superadmin (full access)
- **API Endpoints**: RESTful API untuk mobile/integration
- **Advanced Dashboard Insights**: Data agregat sesuai spek (total_produksi_kg, total_penjualan_rp, etc)
- **Proper Database Schema**: Foreign key constraints dan proper normalization

---

### 📊 **Project Completion Summary**

| Module | Completion | Notes |
|--------|------------|-------|
| **Data Master** | 100% ✅ | Fully functional CRUD |
| **Production** | 100% ✅ | **FK relations implemented** |
| **Sales** | 95% ✅ | **FK relations implemented** |
| **Employees** | 100% ✅ | **FK relations implemented** |
| **Financial** | 70% ⚠️ | Needs KP/BKK separation |
| **Cash Book** | 80% ⚠️ | Works but needs business logic |
| **Debts** | 60% ⚠️ | Missing payment cycle |
| **Dashboard** | 70% ⚠️ | Basic metrics only |
| **User Roles** | 0% ❌ | Not implemented |
| **API** | 0% ❌ | Not implemented |

**Overall Completion: ~80%** (+5% from FK implementation)

### 🎯 **Next Priority Tasks**

1. **HIGH PRIORITY**
   - ~~Fix database schema dengan proper foreign keys~~ ✅ **COMPLETED**
   - Implement KP → BKK auto-create business logic
   - Add debt payment cycle dengan BKK integration
   - Separate financial tables (KP & BKK)

2. **MEDIUM PRIORITY**
   - Add missing tables (`hutang_pembayaran`, `master_debt_types`, etc)
   - Implement user roles & access control
   - Create API endpoints

3. **LOW PRIORITY**
   - Enhance dashboard dengan advanced insights
   - Add unit tests
   - Performance optimization