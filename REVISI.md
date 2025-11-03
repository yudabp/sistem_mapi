# Dokumen Revisi Sistem MAPI

## Summary Revisi

### 1. General
- **Bahasa Sistem**: Mengubah seluruh bahasa sistem dari bahasa Inggris ke bahasa Indonesia untuk konsistensi dengan bahasa yang sudah digunakan di UI

### 2. Data Produksi
- **No Transaksi Otomatis**: Mengubah nomor transaksi agar di-generate otomatis mulai dari TN10250001
- **Validasi TBS dan No Pol**: Mengubah field TBS dan Nomor Polisi (No Pol) menjadi nullable (boleh kosong)

### 3. Data Penjualan
- **Hapus Field Customer**: Menghapus field "Customer Name" dan "Address" dari form input penjualan
- **Format Uang Otomatis**: Input nilai uang langsung terformat dengan pemisah ribuan (titik) saat mengetik

### 4. Data Hutang
- **Filter Hutang Gaji**: Menambahkan fitur filter untuk kategori "Hutang Gaji Karyawan" yang akan menampilkan dropdown nama karyawan dari database

---

## Task List Implementasi

### General - Bahasa Indonesia
- [ ] Ganti label "Production Data" → "Data Produksi"
- [ ] Ganti label "Sales Management" → "Data Penjualan"
- [ ] Ganti label "Financial Management" → "Keuangan Perusahaan"
- [ ] Ganti label "Cash Book" → "Buku Kas"
- [ ] Ganti label "Debt Management" → "Data Hutang"
- [ ] Ganti label "Employee Management" → "Data Karyawan"
- [ ] Ganti label "User Access Control" → "Akses User"
- [ ] Ganti semua label dan pesan validasi ke bahasa Indonesia
- [ ] Update sidebar menu ke bahasa Indonesia
- [ ] Update breadcrumbs ke bahasa Indonesia
- [ ] Update semua tooltips ke bahasa Indonesia

### Data Produksi
- [ ] Buat fungsi generate nomor transaksi otomatis (format: TN + MM + YY + 4 digit urut)
- [ ] Set start number TN10250001
- [ ] 4 digit urut dibelakang otomatis reset setiap awal bulan
- [ ] Implementasi auto-increment untuk nomor transaksi
- [ ] Hapus validasi required pada field TBS di migration dan form
- [ ] Hapus validasi required pada field No Pol di migration dan form
- [ ] Update tampilan tabel untuk menampilkan data kosong jika TBS/No Pol tidak diisi
- [ ] Update form input data produksi
- [ ] Update validasi di Livewire Production.php

### Data Penjualan
- [ ] Hapus kolom "customer_name" dari migration database
- [ ] Hapus kolom "address" dari migration database
- [ ] Update model Sales.php (hapus properti customer_name dan address)
- [ ] Update form input penjualan (hapus field customer dan alamat)
- [ ] Tambahkan JavaScript untuk auto-format mata uang
- [ ] Implementasi format ribuan dengan titik saat mengetik
- [ ] Update validasi untuk menghilangkan required customer
- [ ] Update tampilan tabel penjualan
- [ ] Update export data tanpa kolom customer

### Data Hutang
- [ ] Tambahkan field kategori hutang di form (jika belum ada)
- [ ] Buat dropdown untuk memilih kategori "Hutang Gaji Karyawan"
- [ ] Buat API endpoint untuk mengambil data karyawan
- [ ] Implementasi Alpine.js untuk show/hide dropdown karyawan
- [ ] Ambil data karyawan dari tabel employees
- [ ] Tambahkan validasi jika memilih "Hutang Gaji Karyawan" harus pilih karyawan
- [ ] Update form input hutang dengan filter karyawan
- [ ] Update tampilan daftar hutang dengan info karyawan (jika applicable)
- [ ] Update filter/search data hutang berdasarkan karyawan

### Testing & Validasi
- [ ] Test nomor transaksi otomatis di data produksi
- [ ] Test input data produksi tanpa TBS dan No Pol
- [ ] Test input penjualan tanpa customer
- [ ] Test auto-format uang di form penjualan
- [ ] Test filter hutang gaji karyawan
- [ ] Test dropdown karyawan muncul saat pilih kategori
- [ ] Test semua label dalam bahasa Indonesia
- [ ] Test validasi form dengan pesan bahasa Indonesia

### Database Migration
- [ ] Create migration untuk menghapus customer_name dan address dari sales table
- [ ] Create migration untuk mengubah TBS dan no_pol menjadi nullable di production table
- [ ] Run migration pada environment development
- [ ] Backup database sebelum menjalankan migration
- [ ] Update database seeder untuk sample data

### Dokumentasi
- [ ] Update README.md dengan perubahan fitur
- [ ] Update CLAUDE.md dengan informasi revisi
- [ ] Update user manual (jika ada)
- [ ] Buat catatan rilis untuk versi update