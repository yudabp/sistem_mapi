# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a **Palm Oil Management System (Sistem MAPI)** built with Laravel 11 and Livewire. It's a customized admin dashboard for managing palm oil production, sales, employees, and financial data. The system is based on the Mosaic Lite Laravel template by Cruip but has been extensively modified for palm oil industry specific needs.

## Tech Stack

- **Backend**: Laravel 11, PHP 8.2+
- **Frontend**: Livewire 3.4, Alpine.js, TailwindCSS 4.0, Vite 6
- **Database**: MySQL 8.0
- **Authentication**: Laravel Jetstream with Fortify
- **Development Environment**: Docker (Laravel Sail)

## Key Architecture

### Directory Structure
- `app/Livewire/` - Main Livewire components for each module
- `app/Models/` - Eloquent models for palm oil business entities
- `app/Http/Controllers/PalmOilController.php` - Main controller for palm oil modules
- `resources/views/livewire/` - Blade templates for Livewire components
- `resources/views/palm-oil/` - Main page templates using palm oil layout
- `resources/views/layouts/palm-oil.blade.php` - Custom layout for the system

### Core Modules
The system manages these main business areas:
- **Enhanced Dashboard** (`Dashboard`, `app/Livewire/Dashboard.php`) - Advanced analytics with 7 interactive charts
- **Production Data** (`Production`, `app/Livewire/Production.php`)
- **Sales Management** (`Sales`, `app/Livewire/Sales.php`)
- **Employee Management** (`Employees`, `app/Livewire/Employees.php`)
- **Financial Management** (`Financial`, `app/Livewire/Financial.php`)
- **Cash Book** (`CashBook`, `app/Livewire/CashBook.php`)
- **Debt Management** (`Debts`, `app/Livewire/Debts.php`)
- **User Access Control** (`UserAccess`, `app/Livewire/UserAccess.php`)
- **User Management** - Enhanced CRUD operations for user administration
- **Master Data Management** - Complete CRUD for all master data entities

### Database Schema
Key tables include:
- `employees`, `divisions`, `departments`, `positions` - Employee organization
- `employment_statuses` - Employment status tracking for employees
- `production`, `sales` - Business operations
- `financial_transactions`, `debts` - Financial management
- `pks`, `vehicle_numbers` - Palm oil specific entities
- `family_compositions` - Employee family composition data
- `users` (with `role` column) - User management

## Development Commands

### Docker (Recommended)
```bash
# Quick setup (automated)
./setup-sail.sh

# Manual Docker commands
./vendor/bin/sail up -d          # Start containers
./vendor/bin/sail bash           # Enter container shell
./vendor/bin/sail artisan serve  # Start development server (port 8001)
./vendor/bin/sail npm run dev    # Build frontend assets
```

### Local Development
```bash
# Setup
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate

# Development server
php artisan serve              # Default port 8000
npm run dev                   # Vite development server

# Production build
npm run build
```

### Common Laravel Commands
```bash
php artisan migrate            # Run database migrations
php artisan db:seed           # Seed database with test data
php artisan tinker            # Laravel REPL
php artisan route:list        # List all routes
php artisan cache:clear       # Clear application cache
php artisan config:cache      # Cache configuration
php artisan optimize          # Optimize for production
```

### Testing
```bash
php artisan test              # Run PHPUnit tests
./vendor/bin/pint             # Run Laravel Pint formatter
```

## Key Routes

All main routes are protected by authentication middleware:

### Dashboard Routes
- `/dashboard` - Main dashboard with analytics
- `/dashboard/analytics` - Analytics dashboard
- `/dashboard/fintech` - Fintech dashboard

### Business Operation Routes
- `/data-produksi` - Production data (Indonesian)
- `/data-penjualan` - Sales data (Indonesian)
- `/data-karyawan` - Employee data (Indonesian)
- `/keuangan-perusahaan` - Financial management (Indonesian)
- `/keuangan-perusahaan/buku-kas` - Cash book (Indonesian)
- `/keuangan-perusahaan/data-hutang` - Debt data (Indonesian)

### Master Data Management Routes
- `/master-data/vehicle-numbers` - Vehicle numbers management
- `/master-data/divisions` - Divisions management
- `/master-data/pks` - PKS management
- `/master-data/departments` - Departments management
- `/master-data/positions` - Positions management
- `/master-data/family-compositions` - Family compositions management
- `/master-data/employment-statuses` - Employment statuses management

### User Management Routes
- `/akses-user` - User access management
- `/user-management` - User CRUD operations (Superadmin only)
- `/settings` - System settings

### Sample Data Download Routes
- `/sample/production` - Sample production data CSV
- `/sample/sales` - Sample sales data CSV
- `/sample/financial` - Sample financial data CSV
- `/sample/cash-book` - Sample cash book data CSV
- `/sample/debts` - Sample debts data CSV
- `/sample/employees` - Sample employees data CSV

Both English and Indonesian route names are supported to match the sidebar menu language.

## Frontend Assets

The application uses:
- **Vite** for asset bundling (`vite.config.js`)
- **TailwindCSS 4.0** for styling with custom configuration
- **Alpine.js** for client-side interactivity
- **Chart.js v4.4.7** for data visualization with 7 interactive chart types
- **Flatpickr** for date picking
- **Moment.js** for date handling in charts
- **spatie/laravel-permission** for enhanced role management

Assets are located in:
- `resources/css/app.css` - Main stylesheet
- `resources/js/app.js` - Main JavaScript file
- `resources/js/components/` - Reusable JavaScript components

## Authentication & Authorization

- Uses Laravel Jetstream with Fortify for authentication
- Users have a `role` column for access control (admin, user, superadmin)
- Enhanced role management with spatie/laravel-permission package
- Two-factor authentication is configured but optional
- All routes require authentication and email verification
- Superadmin users have access to user management and all master data

## Development Notes

### Code Style
- Follows Laravel conventions and PSR-12 coding standards
- Uses Laravel Pint for code formatting
- Livewire components follow the pattern: `ComponentName.php` + `component-name.blade.php`

### Database Conventions
- All table names use snake_case
- Foreign keys follow the pattern `{table}_id`
- Timestamps (`created_at`, `updated_at`) are used on all models
- Soft deletes are not implemented by default
- Employee status uses string type instead of enum for flexibility

### Localization
The system primarily uses Indonesian for the user interface but maintains English route names for development consistency.

### Key Features
- **Enhanced Dashboard**: 7 interactive charts with real-time metrics and date range filtering
- **Master Data Management**: Complete CRUD operations for all master data entities
- **Sample Data Downloads**: CSV sample files available for all modules to guide data import
- **Employment Status Tracking**: Dynamic status management for employees
- **Role-based Access Control**: Superadmin, admin, and user roles with appropriate permissions

## Environment Configuration

Key environment variables:
- `DB_CONNECTION=mysql`, `DB_HOST=mysql` (for Docker)
- `APP_PORT=8001` (when using Sail)
- `FORWARD_DB_PORT=3306` (MySQL port mapping)
- `VITE_PORT=5173` (Vite development server)

The automated setup script handles all Docker-specific configuration automatically.

## Recent Updates (Latest Version)

### Feature #12 - Enhanced Dashboard Implementation
- Added 7 interactive charts with Chart.js v4.4.7
- Real-time metrics with date range filtering
- Responsive design with Alpine.js integration
- Production trends, sales trends, financial flow, debt aging, employee distribution, top divisions, and profit margin charts

### Bug #2 - Employment Status Management
- Implemented employment status tracking system
- Changed employee status from enum to string for flexibility
- Added employment status CRUD management
- Enhanced employee filtering by employment status

### Master Data Management System
- Complete CRUD interface for all master data entities
- Centralized management for divisions, departments, positions, PKS, vehicle numbers, family compositions, and employment statuses
- Role-based access control for master data operations

### Sample Data Download Feature
- CSV sample files for all modules
- Guided data import with proper format templates
- Sample routes for production, sales, financial, cash book, debts, and employees data

### User Management Enhancement
- CRUD operations for user administration
- Superadmin middleware protection
- Enhanced role management with spatie/laravel-permission