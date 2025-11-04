<?php

use App\Http\Controllers\CashBookSampleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataFeedController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebtsSampleController;
use App\Http\Controllers\EmployeesSampleController;
use App\Http\Controllers\FinancialSampleController;
use App\Http\Controllers\PalmOilController;
use App\Http\Controllers\ProductionSampleController;
use App\Http\Controllers\SalesSampleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', 'login');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Production sample download
    Route::get('/production/sample/download', [ProductionSampleController::class, 'downloadSample'])->name('production.sample.download');
    
    // Sales sample download
    Route::get('/sales/sample/download', [SalesSampleController::class, 'downloadSample'])->name('sales.sample.download');
    
    // Financial sample download
    Route::get('/financial/sample/download', [FinancialSampleController::class, 'downloadSample'])->name('financial.sample.download');
    
    // CashBook sample download
    Route::get('/cashbook/sample/download', [CashBookSampleController::class, 'downloadSample'])->name('cashbook.sample.download');
    
    // Debts sample download
    Route::get('/debts/sample/download', [DebtsSampleController::class, 'downloadSample'])->name('debts.sample.download');
    
    // Employees sample download
    Route::get('/employees/sample/download', [EmployeesSampleController::class, 'downloadSample'])->name('employees.sample.download');

    // Route for the getting the data feed
    Route::get('/json-data-feed', [DataFeedController::class, 'getDataFeed'])->name('json_data_feed');

    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/analytics', [DashboardController::class, 'analytics'])->name('analytics');
    Route::get('/dashboard/fintech', [DashboardController::class, 'fintech'])->name('fintech');
    
    // Palm Oil Management System routes - English versions
    // Route::get('/production', [App\Http\Controllers\PalmOilController::class, 'production'])->name('production');
    // Route::get('/sales', [App\Http\Controllers\PalmOilController::class, 'sales'])->name('sales');
    // Route::get('/employees', [App\Http\Controllers\PalmOilController::class, 'employees'])->name('employees');
    // Route::get('/financial', [App\Http\Controllers\PalmOilController::class, 'financial'])->name('financial');
    // Route::get('/financial/cash-book', [App\Http\Controllers\PalmOilController::class, 'cashBook'])->name('financial.cash-book');
    // Route::get('/financial/debts', [App\Http\Controllers\PalmOilController::class, 'debts'])->name('financial.debts');

    // Palm Oil Management System routes - Indonesian versions (matching sidebar menu)
    Route::get('/data-produksi', [App\Http\Controllers\PalmOilController::class, 'production'])->name('data-produksi');
    Route::get('/data-produksi/export-pdf', [App\Http\Controllers\ProductionController::class, 'exportPdf'])->name('production.export.pdf');
    Route::get('/data-penjualan', [App\Http\Controllers\PalmOilController::class, 'sales'])->name('data-penjualan');
    Route::get('/data-penjualan/export', [App\Http\Controllers\SalesController::class, 'export'])->name('sales.export');
    Route::get('/data-penjualan/export-pdf', [App\Http\Controllers\SalesController::class, 'exportPdf'])->name('sales.export.pdf');
    Route::get('/data-karyawan', [App\Http\Controllers\PalmOilController::class, 'employees'])->name('data-karyawan');
    Route::get('/data-karyawan/export-pdf', [App\Http\Controllers\EmployeesController::class, 'exportPdf'])->name('employees.export.pdf');
    Route::get('/keuangan-perusahaan', [App\Http\Controllers\PalmOilController::class, 'keuanganPerusahaan'])->name('keuangan-perusahaan');
    Route::get('/keuangan-perusahaan/export-pdf', [App\Http\Controllers\FinancialController::class, 'exportPdf'])->name('financial.export.pdf');
    Route::get('/buku-kas-kebun', [App\Http\Controllers\PalmOilController::class, 'bukuKasKebun'])->name('buku-kas-kebun');
    // Route /keuangan-perusahaan/buku-kas removed - using /buku-kas-kebun instead
    Route::get('/keuangan-perusahaan/buku-kas/export-pdf', [App\Http\Controllers\CashBookController::class, 'exportPdf'])->name('cashbook.export.pdf');
    Route::get('/data-hutang', [App\Http\Controllers\PalmOilController::class, 'debts'])->name('data-hutang');
    Route::get('/data-hutang/export-pdf', [App\Http\Controllers\DebtsController::class, 'exportPdf'])->name('debts.export.pdf');
    Route::get('/akses-user', [App\Http\Controllers\PalmOilController::class, 'userAccess'])->name('akses-user');
    // Manajemen User with CRUD operations (NEW - using palm-oil layout)
    Route::get('/user-management', [App\Http\Controllers\PalmOilController::class, 'userManagement'])->name('user-management')->middleware('role:superadmin');
    // Master Data Routes
    Route::get('/master-data/vehicle-numbers', [App\Http\Controllers\PalmOilController::class, 'vehicleNumbers'])->name('master-data.vehicle-numbers');
    Route::get('/master-data/divisions', [App\Http\Controllers\PalmOilController::class, 'divisions'])->name('master-data.divisions');
    Route::get('/master-data/pks', [App\Http\Controllers\PalmOilController::class, 'pks'])->name('master-data.pks');
    Route::get('/master-data/departments', [App\Http\Controllers\PalmOilController::class, 'departments'])->name('master-data.departments');
    Route::get('/master-data/positions', [App\Http\Controllers\PalmOilController::class, 'positions'])->name('master-data.positions');
    Route::get('/master-data/family-compositions', [App\Http\Controllers\PalmOilController::class, 'familyCompositions'])->name('master-data.family-compositions');
    Route::get('/master-data/employment-statuses', [App\Http\Controllers\PalmOilController::class, 'employmentStatuses'])->name('master-data.employment-statuses');

    // Legacy settings route (redirect to first master data item)
    // Route::get('/settings', [App\Http\Controllers\PalmOilController::class, 'vehicleNumbers'])->name('settings');

    // Route::get('/messages', function () {
    //     return view('pages/messages');
    // })->name('messages');
    // Route::get('/tasks/kanban', function () {
    //     return view('pages/tasks/tasks-kanban');
    // })->name('tasks-kanban');
    // Route::get('/tasks/list', function () {
    //     return view('pages/tasks/tasks-list');
    // })->name('tasks-list');       
    // Route::get('/inbox', function () {
    //     return view('pages/inbox');
    // })->name('inbox'); 
    // Route::get('/calendar', function () {
    //     return view('pages/calendar');
    // })->name('calendar'); 
    // Route::get('/settings/account', function () {
    //     return view('pages/settings/account');
    // })->name('account');  
    // Route::get('/settings/notifications', function () {
    //     return view('pages/settings/notifications');
    // })->name('notifications');  
    // Route::get('/settings/apps', function () {
    //     return view('pages/settings/apps');
    // })->name('apps');
    // Route::get('/settings/plans', function () {
    //     return view('pages/settings/plans');
    // })->name('plans');      
    // Route::get('/settings/billing', function () {
    //     return view('pages/settings/billing');
    // })->name('billing');  
    // Route::get('/settings/feedback', function () {
    //     return view('pages/settings/feedback');
    // })->name('feedback');
    // Route::get('/utility/changelog', function () {
    //     return view('pages/utility/changelog');
    // })->name('changelog');  
    // Route::get('/utility/roadmap', function () {
    //     return view('pages/utility/roadmap');
    // })->name('roadmap');  
    // Route::get('/utility/faqs', function () {
    //     return view('pages/utility/faqs');
    // })->name('faqs');  
    // Route::get('/utility/empty-state', function () {
    //     return view('pages/utility/empty-state');
    // })->name('empty-state');  
    // Route::get('/utility/404', function () {
    //     return view('pages/utility/404');
    // })->name('404');
    // Route::get('/utility/knowledge-base', function () {
    //     return view('pages/utility/knowledge-base');
    // })->name('knowledge-base');
    // Route::get('/onboarding-01', function () {
    //     return view('pages/onboarding-01');
    // })->name('onboarding-01');   
    // Route::get('/onboarding-02', function () {
    //     return view('pages/onboarding-02');
    // })->name('onboarding-02');   
    // Route::get('/onboarding-03', function () {
    //     return view('pages/onboarding-03');
    // })->name('onboarding-03');   
    // Route::get('/onboarding-04', function () {
    //     return view('pages/onboarding-04');
    // })->name('onboarding-04');
    // Route::get('/component/button', function () {
    //     return view('pages/component/button-page');
    // })->name('button-page');
    // Route::get('/component/form', function () {
    //     return view('pages/component/form-page');
    // })->name('form-page');
    // Route::get('/component/dropdown', function () {
    //     return view('pages/component/dropdown-page');
    // })->name('dropdown-page');
    // Route::get('/component/alert', function () {
    //     return view('pages/component/alert-page');
    // })->name('alert-page');
    // Route::get('/component/modal', function () {
    //     return view('pages/component/modal-page');
    // })->name('modal-page'); 
    // Route::get('/component/pagination', function () {
    //     return view('pages/component/pagination-page');
    // })->name('pagination-page');
    // Route::get('/component/tabs', function () {
    //     return view('pages/component/tabs-page');
    // })->name('tabs-page');
    // Route::get('/component/breadcrumb', function () {
    //     return view('pages/component/breadcrumb-page');
    // })->name('breadcrumb-page');
    // Route::get('/component/badge', function () {
    //     return view('pages/component/badge-page');
    // })->name('badge-page'); 
    // Route::get('/component/avatar', function () {
    //     return view('pages/component/avatar-page');
    // })->name('avatar-page');
    // Route::get('/component/tooltip', function () {
    //     return view('pages/component/tooltip-page');
    // })->name('tooltip-page');
    // Route::get('/component/accordion', function () {
    //     return view('pages/component/accordion-page');
    // })->name('accordion-page');
    // Route::get('/component/icons', function () {
    //     return view('pages/component/icons-page');
    // })->name('icons-page');
    // Route::fallback(function() {
    //     return view('pages/utility/404');
    // });    
});
