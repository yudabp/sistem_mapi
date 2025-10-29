<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Production;
use App\Models\Sale;
use App\Models\FinancialTransaction;
use App\Models\Debt;
use App\Models\KeuanganPerusahaan;
use App\Models\BukuKasKebun;
use App\Models\Department;
use App\Models\Division;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $totalProduction = 0;
    public $totalSales = 0;
    public $totalIncome = 0;
    public $totalExpenses = 0;
    public $totalDebt = 0;
    public $totalEmployees = 0;
    public $activeEmployees = 0;
    public $unpaidDebtsCount = 0;
    public $productionThisMonth = 0;
    public $salesThisMonth = 0;
    public $profitThisMonth = 0;
    public $dateRange = 'this_month';
    public $startDate;
    public $endDate;

    // Percentage changes
    public $productionChange = 0;
    public $salesChange = 0;
    public $incomeChange = 0;
    public $expensesChange = 0;

    // Chart data
    public $productionData = [];
    public $salesData = [];
    public $financialData = [];
    public $debtAgingData = [];
    public $employeeDistributionData = [];
    public $topDivisions = [];
    public $profitMarginData = [];

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
        $this->updateMetrics();
        $this->prepareChartData();
    }

    public function render()
    {
        // Debug untuk memastikan data terisi
        if (request()->has('debug')) {
            dd([
                'productionData' => $this->productionData,
                'salesData' => $this->salesData,
                'financialData' => $this->financialData,
                'debtAgingData' => $this->debtAgingData,
                'employeeDistributionData' => $this->employeeDistributionData,
                'topDivisions' => $this->topDivisions,
            ]);
        }

        return view('livewire.dashboard', [
            'totalProduction' => $this->totalProduction,
            'totalSales' => $this->totalSales,
            'totalIncome' => $this->totalIncome,
            'totalExpenses' => $this->totalExpenses,
            'totalDebt' => $this->totalDebt,
            'totalEmployees' => $this->totalEmployees,
            'activeEmployees' => $this->activeEmployees,
            'unpaidDebtsCount' => $this->unpaidDebtsCount,
            'productionThisMonth' => $this->productionThisMonth,
            'salesThisMonth' => $this->salesThisMonth,
            'profitThisMonth' => $this->profitThisMonth,
            'productionData' => $this->productionData ?: [],
            'salesData' => $this->salesData ?: [],
            'financialData' => $this->financialData ?: [],
            'debtAgingData' => $this->debtAgingData ?: [],
            'employeeDistributionData' => $this->employeeDistributionData ?: [],
            'topDivisions' => $this->topDivisions ?: [],
            'profitMarginData' => $this->profitMarginData ?: [],
            'productionChange' => $this->productionChange,
            'salesChange' => $this->salesChange,
            'incomeChange' => $this->incomeChange,
            'expensesChange' => $this->expensesChange,
        ]);
    }

    public function updateMetrics()
    {
        // Parse dates
        $startDate = Carbon::parse($this->startDate)->startOfDay();
        $endDate = Carbon::parse($this->endDate)->endOfDay();

        // Calculate previous period dates for comparison
        $duration = (int)$startDate->diffInDays($endDate) + 1;
        $previousEndDate = $startDate->copy()->subDay();
        $previousStartDate = $previousEndDate->copy()->subDays($duration - 1);

        // Calculate total production within date range
        $this->totalProduction = Production::whereBetween('date', [$startDate, $endDate])
            ->sum('kg_quantity');

        // Calculate previous period production
        $previousProduction = Production::whereBetween('date', [$previousStartDate, $previousEndDate])
            ->sum('kg_quantity');
        $this->productionChange = $this->calculatePercentageChange($this->totalProduction, $previousProduction);

        // Calculate total sales amount within date range
        $this->totalSales = Sale::whereBetween('sale_date', [$startDate, $endDate])
            ->sum('total_amount');

        // Calculate previous period sales
        $previousSales = Sale::whereBetween('sale_date', [$previousStartDate, $previousEndDate])
            ->sum('total_amount');
        $this->salesChange = $this->calculatePercentageChange($this->totalSales, $previousSales);

        // Calculate total income from both tables within date range
        $kpIncome = KeuanganPerusahaan::where('transaction_type', 'income')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');
        $bkkIncome = BukuKasKebun::where('transaction_type', 'income')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');
        $this->totalIncome = $kpIncome + $bkkIncome;

        // Calculate previous period income
        $previousKpIncome = KeuanganPerusahaan::where('transaction_type', 'income')
            ->whereBetween('transaction_date', [$previousStartDate, $previousEndDate])
            ->sum('amount');
        $previousBkkIncome = BukuKasKebun::where('transaction_type', 'income')
            ->whereBetween('transaction_date', [$previousStartDate, $previousEndDate])
            ->sum('amount');
        $previousIncome = $previousKpIncome + $previousBkkIncome;
        $this->incomeChange = $this->calculatePercentageChange($this->totalIncome, $previousIncome);

        // Calculate total expenses from both tables within date range
        $kpExpenses = KeuanganPerusahaan::where('transaction_type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');
        $bkkExpenses = BukuKasKebun::where('transaction_type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');
        $this->totalExpenses = $kpExpenses + $bkkExpenses;

        // Calculate previous period expenses
        $previousKpExpenses = KeuanganPerusahaan::where('transaction_type', 'expense')
            ->whereBetween('transaction_date', [$previousStartDate, $previousEndDate])
            ->sum('amount');
        $previousBkkExpenses = BukuKasKebun::where('transaction_type', 'expense')
            ->whereBetween('transaction_date', [$previousStartDate, $previousEndDate])
            ->sum('amount');
        $previousExpenses = $previousKpExpenses + $previousBkkExpenses;
        $this->expensesChange = $this->calculatePercentageChange($this->totalExpenses, $previousExpenses);

        // Calculate total debt (active debts regardless of date)
        $this->totalDebt = Debt::where('status', 'unpaid')->sum('amount');

        // Count total employees (current employees regardless of date)
        $this->totalEmployees = Employee::count();
        $this->activeEmployees = Employee::where('status', 'aktif')->count();

        // Count unpaid debts
        $this->unpaidDebtsCount = Debt::where('status', 'unpaid')->count();

        // Store period metrics
        $this->productionThisMonth = $this->totalProduction;
        $this->salesThisMonth = $this->totalSales;

        // Calculate profit (sales - cost of goods sold approximation)
        $this->profitThisMonth = $this->salesThisMonth * 0.25; // Assuming 25% profit margin
    }

    private function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        $change = (($current - $previous) / $previous) * 100;
        return round($change, 1);
    }

    public function prepareChartData()
    {
        // Production trends (last 6 months)
        $this->productionData = $this->getProductionTrends();

        // Sales data
        $this->salesData = $this->getSalesTrends();

        // Financial flow data
        $this->financialData = $this->getFinancialFlowData();

        // Debt aging data
        $this->debtAgingData = $this->getDebtAgingData();

        // Employee distribution
        $this->employeeDistributionData = $this->getEmployeeDistribution();

        // Top producing divisions
        $this->topDivisions = $this->getTopDivisions();

        // Profit margin data
        $this->profitMarginData = $this->getProfitMarginData();
    }

    private function getProductionTrends()
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M');
            $production = Production::whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('kg_quantity');
            $data[$monthName] = $production;
        }
        return $data;
    }

    private function getSalesTrends()
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M');
            $sales = Sale::whereMonth('sale_date', $month->month)
                ->whereYear('sale_date', $month->year)
                ->sum('total_amount');
            $data[$monthName] = $sales;
        }
        return $data;
    }

    private function getFinancialFlowData()
    {
        $data = [
            'income' => [],
            'expense' => [],
            'months' => []
        ];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M');
            $data['months'][] = $monthName;

            $income = KeuanganPerusahaan::whereMonth('transaction_date', $month->month)
                ->whereYear('transaction_date', $month->year)
                ->where('transaction_type', 'income')
                ->sum('amount');
            $data['income'][] = $income;

            $expense = KeuanganPerusahaan::whereMonth('transaction_date', $month->month)
                ->whereYear('transaction_date', $month->year)
                ->where('transaction_type', 'expense')
                ->sum('amount');
            $data['expense'][] = $expense;
        }

        return $data;
    }

    private function getDebtAgingData()
    {
        $now = Carbon::now();
        $data = [
            '0-30' => 0,
            '31-60' => 0,
            '61-90' => 0,
            '>90' => 0
        ];

        $debts = Debt::where('status', 'unpaid')->get();

        foreach ($debts as $debt) {
            $daysDue = $now->diffInDays($debt->due_date);

            if ($daysDue <= 30) {
                $data['0-30'] += $debt->amount;
            } elseif ($daysDue <= 60) {
                $data['31-60'] += $debt->amount;
            } elseif ($daysDue <= 90) {
                $data['61-90'] += $debt->amount;
            } else {
                $data['>90'] += $debt->amount;
            }
        }

        return $data;
    }

    private function getEmployeeDistribution()
    {
        $departments = Department::withCount('employees')->get();
        $data = [];

        foreach ($departments as $dept) {
            if ($dept->employees_count > 0) {
                $data[$dept->name] = $dept->employees_count;
            }
        }

        return $data;
    }

    private function getTopDivisions()
    {
        return Division::withSum('productions', 'kg_quantity')
            ->orderByDesc('productions_sum_kg_quantity')
            ->limit(5)
            ->get()
            ->map(function ($division) {
                return [
                    'name' => $division->name,
                    'production' => $division->productions_sum_kg_quantity ?? 0
                ];
            });
    }

    private function getProfitMarginData()
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M');

            // Calculate total income and expenses for the month
            $income = KeuanganPerusahaan::whereMonth('transaction_date', $month->month)
                ->whereYear('transaction_date', $month->year)
                ->where('transaction_type', 'income')
                ->sum('amount');

            $expense = KeuanganPerusahaan::whereMonth('transaction_date', $month->month)
                ->whereYear('transaction_date', $month->year)
                ->where('transaction_type', 'expense')
                ->sum('amount');

            // Calculate profit margin percentage
            $margin = 0;
            if ($income > 0) {
                $margin = (($income - $expense) / $income) * 100;
            }

            $data[$monthName] = round($margin, 2);
        }
        return $data;
    }

    public function updateDateRange()
    {
        $this->updateDateRangeValues();
        $this->updateMetrics();
        $this->prepareChartData();
    }

    private function updateDateRangeValues()
    {
        $now = Carbon::now();

        switch ($this->dateRange) {
            case 'this_week':
                $this->startDate = $now->startOfWeek()->toDateString();
                $this->endDate = $now->endOfWeek()->toDateString();
                break;
            case 'last_week':
                $this->startDate = $now->subWeek()->startOfWeek()->toDateString();
                $this->endDate = $now->subWeek()->endOfWeek()->toDateString();
                break;
            case 'this_month':
                $this->startDate = $now->startOfMonth()->toDateString();
                $this->endDate = $now->endOfMonth()->toDateString();
                break;
            case 'last_month':
                $this->startDate = $now->subMonth()->startOfMonth()->toDateString();
                $this->endDate = $now->subMonth()->endOfMonth()->toDateString();
                break;
            case 'this_quarter':
                $this->startDate = $now->startOfQuarter()->toDateString();
                $this->endDate = $now->endOfQuarter()->toDateString();
                break;
            case 'this_year':
                $this->startDate = $now->startOfYear()->toDateString();
                $this->endDate = $now->endOfYear()->toDateString();
                break;
        }
    }

    // Refresh metrics when component updates
    public function updated()
    {
        $this->updateMetrics();
    }

    // Refresh charts - triggers JavaScript to reinitialize charts
    public function refreshCharts()
    {
        // This method is called by Livewire hooks to trigger chart refresh
        // The actual chart refresh happens via JavaScript
    }
}
