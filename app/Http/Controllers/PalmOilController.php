<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PalmOilController extends Controller
{
    public function dashboard()
    {
        return view('palm-oil.dashboard');
    }

    public function production()
    {
        return view('palm-oil.production');
    }

    public function sales()
    {
        return view('palm-oil.sales');
    }

    public function employees()
    {
        return view('palm-oil.employees');
    }

    public function financial()
    {
        return view('palm-oil.financial');
    }

    public function keuanganPerusahaan()
    {
        return view('palm-oil.keuangan-perusahaan');
    }

    public function bukuKasKebun()
    {
        return view('palm-oil.buku-kas-kebun');
    }

    public function cashBook()
    {
        return view('palm-oil.cash-book');
    }

    public function debts()
    {
        return view('palm-oil.debts');
    }

    public function userAccess()
    {
        return view('palm-oil.user-access');
    }
    
    // Master Data Methods
    public function vehicleNumbers()
    {
        return view('palm-oil.master-data.vehicle-numbers');
    }

    public function divisions()
    {
        return view('palm-oil.master-data.divisions');
    }

    public function pks()
    {
        return view('palm-oil.master-data.pks');
    }

    public function departments()
    {
        return view('palm-oil.master-data.departments');
    }

    public function positions()
    {
        return view('palm-oil.master-data.positions');
    }

    public function familyCompositions()
    {
        return view('palm-oil.master-data.family-compositions');
    }

    public function employmentStatuses()
    {
        return view('palm-oil.master-data.employment-statuses');
    }

    public function settings()
    {
        return view('palm-oil.settings');
    }

    public function userManagement()
    {
        return view('palm-oil.user-management');
    }
}
