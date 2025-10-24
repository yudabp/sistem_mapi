# App Summary: PT API APPS - Sistem Manajemen Kebun Sawit

## Overview
PT API APPS is a comprehensive palm oil plantation management system designed to streamline operations across multiple business areas including production, sales, finance, and employee management. The system provides a centralized dashboard with real-time data visualization and detailed management modules for different operational aspects.

## Application Features

### 1. Dashboard (index.html)
- **Real-time Summary Cards**: Displays key metrics including Total Production, Total Sales, Total Income, Total Expenses, Total Debt, and Number of Employees
- **Navigation Menu**: Sidebar navigation to all major modules
- **Responsive Design**: Clean interface with hover effects and card-based layout
- **Visual Indicators**: Color-coded icons and metrics for quick understanding

### 2. Production Management (page-data-produksi.html)
- **Data Input Form**: 
  - Transaction number, date, SP number, vehicle number
  - TBS (Fresh Fruit Bunches) and KG quantities
  - Division (Afdeling) and Processing Station (PKS) selection
  - SP photo upload capability
- **Search & Filter**: Filtering by date, division, and PKS
- **Export Functions**: Excel and PDF export capabilities
- **Summary Dashboard**: Total TBS and KG counters
- **Data Table**: Display of all production records with sorting capabilities

### 3. Sales Management (page-data-penjualan.html)
- **Sales Input Form**:
  - SP number selection with associated TBS/KG data
  - Price per kg input with automatic total calculation
  - Sales proof upload
- **Dynamic Calculations**: Automatic total calculation based on quantity and price
- **Filter & Search**: Filtering capabilities for data retrieval
- **Export Functions**: Excel and PDF export options
- **Summary Dashboard**: Total KG and sales amount counters
- **Data Table**: Sales records display with proof viewing

### 4. Employee Management (page-data-karyawan.html)
- **Employee Registration Form**:
  - Employee ID (NDP), name, department, position
  - Grade, family composition, monthly salary
  - Employment status selection
- **Advanced Table**: DataTables with export capabilities (Excel, CSV, PDF, Print)
- **Summary Dashboard**: Employee count and total salary calculations
- **Real-time Calculations**: Automatic updates to employee count and total salary
- **Responsive Design**: Grid-based form layout

### 5. Financial Management
#### 5.1 Keuangan Perusahaan (KP) - Company Financial Management
- **Transaction Input Form**:
  - Date, automatic transaction number (KP prefix)
  - Transaction type (Income/Expense)
  - Amount, source/destination, received by
  - Proof document upload
  - Notes field, category selection
- **Financial Summary**: Income, expense, and balance calculations
- **Advanced Filtering**: Date range, transaction type, search functionality
- **Export Functions**: CSV and PDF export
- **Document Preview**: Image preview and file download for proof documents
- **Responsive Design**: Grid layout with Tailwind CSS
- **Categories**: Sales Revenue, Personnel Cost, Administrative Cost, Financial Cost, Tax, Investment, Loan, Other Income, Other Expense

#### 5.2 Buku Kas Kebun (BKK) - Garden Cash Book Management
- **Cash Transaction Form**:
  - Date, automatic transaction number (BKK prefix)
  - Transaction type (Income/Expense)
  - Amount, purpose, description
  - Proof document upload, notes
  - Category selection, KP transaction linking
- **Financial Summary**: Income, expense, and balance tracking
- **Export Functions**: CSV and PDF export
- **KP Integration**: Links to Keuangan Perusahaan transactions for audit trail
- **Categories**: Operational Cost, Maintenance Cost, Logistics Cost, Harvesting Cost, Fertilizer Cost, Pest Control Cost, Transportation Cost, Fuel Cost

#### 5.3 KP → BKK Auto-create Business Logic
- **Automatic Transaction Creation**: When KP expense is created, corresponding BKK income entry is auto-generated
- **Audit Trail**: Maintains relationship between KP and BKK transactions via foreign key
- **Data Integrity**: Ensures financial consistency between company and garden-level transactions
- **Error Handling**: Rollback mechanisms for failed auto-creations
- **Logging**: Comprehensive audit trail for all auto-created transactions

#### 5.3 Debt Management (page-datakeuangan-subdatahutang.html)
- **Debt Registration Form**:
  - Debt amount, creditor, due date
  - Description, proof document upload
- **Status Tracking**: Debt status (Unpaid/Paid) with marking functionality
- **Filtering**: Search by creditor name
- **Summary Dashboard**: Total debt, paid amount, remaining debt
- **Action Buttons**: Mark as paid functionality

## Application Workflow

### 1. User Navigation Flow
```
Dashboard → Select Module → Input Data → View/Manage Data → Export Reports
```

### 2. Data Entry Workflow
1. **Access appropriate module** from sidebar navigation
2. **Fill data entry form** with required information
3. **Submit data** which gets stored in memory/DB
4. **View data** in the table below the form
5. **Filter/search** for specific records if needed
6. **Export data** in required format (Excel/PDF/CSV)

### 3. Financial Operations Workflow
1. **Daily transactions** recorded in cash book
2. **Debt tracking** for outstanding payments
3. **Income/expense categorization** for financial analysis
4. **Monthly reconciliation** using summary reports

### 4. Production to Sales Workflow
1. **Production records** entered after harvest
2. **Sales records** linked to production data
3. **Financial entries** created for sales transactions
4. **Performance analysis** through dashboard metrics

## Technical Specifications

### Frontend Technologies Used
- HTML5
- CSS3 with responsive design
- JavaScript for interactive functionality
- jQuery for DOM manipulation
- DataTables for advanced table features
- Bootstrap for UI components
- Tailwind CSS for modern styling
- jsPDF and html2canvas for PDF generation

### Functional Specifications
- **Responsive Design**: Works on desktop and tablet devices
- **Real-time Calculations**: Automatic computation of totals and balances
- **File Upload**: Support for image and document uploads
- **Data Validation**: Client-side validation for form inputs
- **Export Capabilities**: Multiple export formats (Excel, PDF, CSV)
- **Search & Filter**: Advanced filtering options for data retrieval
- **Interactive UI**: Hover effects, animations, and user feedback

### Data Models Identified
1. **Employee Model**: NDP, name, department, position, grade, family status, salary, status
2. **Production Model**: Transaction ID, date, SP number, vehicle number, TBS, KG, division, PKS
3. **Sales Model**: SP number, TBS, KG, price, total, sales proof, tax information
4. **Keuangan Perusahaan (KP) Model**: Transaction date, number (KP prefix), type, amount, source/destination, received by, proof, notes, category
5. **Buku Kas Kebun (BKK) Model**: Transaction date, number (BKK prefix), type, amount, source/destination, received by, proof, notes, category, KP foreign key
6. **Debt Model**: Amount, creditor, due date, description, proof, status, payment history

## Proposed Laravel Livewire Implementation

### Backend Architecture
- **Laravel Framework**: For robust backend functionality
- **Livewire Components**: For dynamic, reactive UI without complex JavaScript
- **Eloquent Models**: For database operations
- **Blade Templates**: For server-side rendering
- **Tailwind CSS v4**: For modern, utility-first styling

### Key Features Implementation
1. **Real-time Dashboard**: Using Livewire's reactivity for live updates
2. **Form Validation**: Laravel's built-in validation with Livewire integration
3. **File Uploads**: Laravel's file upload functionality with Livewire
4. **Data Export**: Laravel Excel package for export functionality
5. **Search & Filter**: Server-side filtering with Livewire actions
6. **Authentication**: Laravel's built-in auth system for user management
7. **Financial Separation**: KP and BKK tables with proper categorization and auto-creation logic
8. **Tax Management**: Automated tax calculation for sales transactions
9. **SP Number Autocomplete**: Real-time search functionality for production data integration
10. **Foreign Key Relations**: Proper database relationships with integrity constraints

### Database Structure
- **employees** table: Employee information
- **production** table: Production records
- **sales** table: Sales transactions with tax information
- **keuangan_perusahaan** table: Company-level financial transactions (KP)
- **buku_kas_kebun** table: Garden-level financial transactions (BKK) with KP foreign key
- **debts** table: Debt records with payment tracking
- **Master tables**: divisions, departments, positions, vehicle_numbers, pks, family_compositions, employment_statuses

### Security Considerations
- User authentication and authorization
- Input validation and sanitization
- File upload security
- Database query protection
- Session management

### Performance Optimizations
- Eloquent query optimization
- Pagination for large datasets
- Caching for frequently accessed data
- Asset optimization and minification
- Database indexing strategies

This comprehensive system provides an integrated solution for palm oil plantation management with focus on data visualization, operational efficiency, and financial tracking.
