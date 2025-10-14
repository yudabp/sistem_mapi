<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Production;
use App\Models\Sale;
use App\Models\FinancialTransaction;
use App\Models\Debt;

class Dashboard extends Component
{
    public $totalProduction = 0;
    public $totalSales = 0;
    public $totalIncome = 0;
    public $totalExpenses = 0;
    public $totalDebt = 0;
    public $totalEmployees = 0;

    public function mount()
    {
        $this->updateMetrics();
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'totalProduction' => $this->totalProduction,
            'totalSales' => $this->totalSales,
            'totalIncome' => $this->totalIncome,
            'totalExpenses' => $this->totalExpenses,
            'totalDebt' => $this->totalDebt,
            'totalEmployees' => $this->totalEmployees,
        ]);
    }

    public function updateMetrics()
    {
        // Calculate total production (sum of TBS or KG quantities)
        $this->totalProduction = Production::sum('kg_quantity');

        // Calculate total sales amount
        $this->totalSales = Sale::sum('total_amount');

        // Calculate total income and expenses from financial transactions
        $this->totalIncome = FinancialTransaction::where('transaction_type', 'income')->sum('amount');
        $this->totalExpenses = FinancialTransaction::where('transaction_type', 'expense')->sum('amount');

        // Calculate total debt
        $this->totalDebt = Debt::where('status', 'unpaid')->sum('amount');

        // Count total employees
        $this->totalEmployees = Employee::count();
    }

    // Refresh metrics when component updates
    public function updated()
    {
        $this->updateMetrics();
    }
}
