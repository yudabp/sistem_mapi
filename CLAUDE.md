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
- **Production Data** (`Production`, `app/Livewire/Production.php`)
- **Sales Management** (`Sales`, `app/Livewire/Sales.php`)
- **Employee Management** (`Employees`, `app/Livewire/Employees.php`)
- **Financial Management** (`Financial`, `app/Livewire/Financial.php`)
- **Cash Book** (`CashBook`, `app/Livewire/CashBook.php`)
- **Debt Management** (`Debts`, `app/Livewire/Debts.php`)
- **User Access Control** (`UserAccess`, `app/Livewire/UserAccess.php`)

### Database Schema
Key tables include:
- `employees`, `divisions`, `departments`, `positions` - Employee organization
- `production`, `sales` - Business operations
- `financial_transactions`, `debts` - Financial management
- `pks`, `vehicle_numbers` - Palm oil specific entities
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
- `/dashboard` - Main dashboard
- `/data-produksi` - Production data (Indonesian)
- `/data-penjualan` - Sales data (Indonesian)
- `/data-karyawan` - Employee data (Indonesian)
- `/keuangan-perusahaan` - Financial management (Indonesian)
- `/keuangan-perusahaan/buku-kas` - Cash book (Indonesian)
- `/keuangan-perusahaan/data-hutang` - Debt data (Indonesian)
- `/akses-user` - User access management
- `/settings` - System settings

Both English and Indonesian route names are supported to match the sidebar menu language.

## Frontend Assets

The application uses:
- **Vite** for asset bundling (`vite.config.js`)
- **TailwindCSS 4.0** for styling with custom configuration
- **Alpine.js** for client-side interactivity
- **Chart.js** for data visualization
- **Flatpickr** for date picking

Assets are located in:
- `resources/css/app.css` - Main stylesheet
- `resources/js/app.js` - Main JavaScript file
- `resources/js/components/` - Reusable JavaScript components

## Authentication & Authorization

- Uses Laravel Jetstream with Fortify for authentication
- Users have a `role` column for access control
- Two-factor authentication is configured but optional
- All routes require authentication and email verification

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

### Localization
The system primarily uses Indonesian for the user interface but maintains English route names for development consistency.

## Environment Configuration

Key environment variables:
- `DB_CONNECTION=mysql`, `DB_HOST=mysql` (for Docker)
- `APP_PORT=8001` (when using Sail)
- `FORWARD_DB_PORT=3306` (MySQL port mapping)
- `VITE_PORT=5173` (Vite development server)

The automated setup script handles all Docker-specific configuration automatically.