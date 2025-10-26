# Sample Data Files

This directory contains sample CSV files for importing data into the PT API APPS system.

## Available Modules with Import Functionality

1. 🌾 **Data Produksi** (Production Data)
2. 💰 **Data Penjualan** (Sales Data) 
3. 👥 **Data Karyawan** (Employee Data)
4. 🏢 **Keuangan Perusahaan** (Company Financials)
5. 📑 **Buku Kas** (Cash Book)
6. 💳 **Data Hutang** (Debt Data)

## Sample Files by Module

### 🌾 Data Produksi (Production Data)
Files: `sample_production_data_5.csv`, `sample_production_data_10.csv`, `sample_production_data_20.csv`

**Fields:**
- `transaction_number` - Unique transaction identifier (e.g., TRX001)
- `date` - Production date in MM/DD/YYYY format (e.g., 10/25/2025)
- `sp_number` - Surat Permintaan (SP) number (e.g., SP001)
- `vehicle_number` - Vehicle license plate number (e.g., B1234XYZ)
- `tbs_quantity` - Fresh Fruit Bunches quantity (e.g., 1000.5)
- `kg_quantity` - Kilogram quantity (e.g., 950.2)
- `division` - Division/Afdeling name (e.g., Afdeling A)
- `pks` - Palm Kernel Station (PKS) name (e.g., PKS 1)

### 💰 Data Penjualan (Sales Data)
File: `sample_sales_data_5.csv`

**Fields:**
- `sp_number` - Surat Permintaan (SP) number (e.g., SP010)
- `tbs_quantity` - TBS quantity from production (e.g., 1000.5)
- `kg_quantity` - KG quantity from production (e.g., 950.2)
- `price_per_kg` - Price per kilogram (e.g., 2500)
- `total_amount` - Total sales amount (e.g., 2375500)
- `sale_date` - Sale date in MM/DD/YYYY format (e.g., 10/25/2025)
- `customer_name` - Customer/company name (e.g., PT Sawit Makmur)
- `customer_address` - Customer address (e.g., Jl. Raya Sawit No. 123, Medan)

### 👥 Data Karyawan (Employee Data)
File: `sample_employees_data_5.csv`

**Fields:**
- `ndp` - Nomor Daftar Pegawai/Employee ID (e.g., NDP010)
- `name` - Employee full name (e.g., Andi Prasetyo)
- `department` - Department/Bagian (e.g., Afdeling 1)
- `position` - Job position/Jabatan (e.g., Mandor)
- `grade` - Salary grade/Golongan (e.g., B)
- `family_composition` - Family composition/Susunan Keluarga (e.g., 3)
- `monthly_salary` - Monthly salary/Gaji Bulanan (e.g., 3500000)
- `status` - Employment status (e.g., active)
- `hire_date` - Hire date in YYYY-MM-DD format (e.g., 2023-01-15)
- `address` - Home address (e.g., Jl. Merdeka No. 10)
- `phone` - Phone number (e.g., 081234567890)
- `email` - Email address (e.g., andi.p@example.com)

### 🏢 Keuangan Perusahaan (Company Financials)
File: `sample_financial_data_5.csv`

**Fields:**
- `transaction_date` - Transaction date in MM/DD/YYYY format (e.g., 10/25/2025)
- `transaction_type` - Type: income or expense (e.g., income)
- `amount` - Transaction amount in Rupiah (e.g., 15000000)
- `source_destination` - Source (for income) or destination (for expense) (e.g., PT Sawit Makmur)
- `received_by` - Person who handled the transaction (e.g., Budi Santoso)
- `notes` - Additional notes (e.g., Pembayaran penjualan bulan ini)
- `category` - Transaction category (e.g., Sales Revenue)

### 📑 Buku Kas (Cash Book)
File: `sample_cashbook_data_5.csv`

**Fields:**
- `transaction_date` - Transaction date in MM/DD/YYYY format (e.g., 10/25/2025)
- `transaction_type` - Type: income or expense (e.g., expense)
- `amount` - Transaction amount in Rupiah (e.g., 2500000)
- `purpose` - Purpose of transaction (e.g., Fuel Purchase)
- `notes` - Additional notes (e.g., Bensin kendaraan operasional)
- `category` - Transaction category (e.g., Transportation Cost)

### 💳 Data Hutang (Debt Data)
File: `sample_debts_data_5.csv`

**Fields:**
- `amount` - Debt amount in Rupiah (e.g., 50000000)
- `creditor` - Creditor/Pemberi Hutang (e.g., Bank Mandiri)
- `due_date` - Due date in MM/DD/YYYY format (e.g., 11/25/2025)
- `description` - Debt description (e.g., Pembelian alat produksi)
- `status` - Status: unpaid or paid (e.g., unpaid)
- `paid_date` - Payment date in MM/DD/YYYY format (e.g., 10/30/2025) - only for paid debts

## Date Format Note

The date format in these samples varies by module:
- **Production Data**: MM/DD/YYYY (e.g., 10/25/2025)
- **Sales Data**: MM/DD/YYYY (e.g., 10/25/2025)
- **Employee Data**: YYYY-MM-DD (e.g., 2023-01-15)
- **Financial Data**: MM/DD/YYYY (e.g., 10/25/2025)
- **Cash Book Data**: MM/DD/YYYY (e.g., 10/25/2025)
- **Debt Data**: MM/DD/YYYY (e.g., 11/25/2025)

The import system has been updated to handle flexible date formats including:
- MM/DD/YYYY (US format)
- DD/MM/YYYY (European format)  
- YYYY-MM-DD (ISO format)
- MM-DD-YYYY (Alternative format)

## Usage Instructions

1. Navigate to the appropriate data module in the application
2. Click the "Import" button
3. Select one of the sample files
4. The system will automatically create related records where needed
5. View the imported data in the respective module tables

## Notes

- All sample data is fictional and for testing purposes only
- The import system will automatically handle creation of related entities
- Duplicate entries will be rejected to maintain data integrity
- Quantities and amounts are in standard units (TBS/units, KG/kg, currency in Rupiah)