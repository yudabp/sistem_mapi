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
    
    public function settings()
    {
        return view('palm-oil.settings');
    }

    public function userManagement()
    {
        return view('palm-oil.user-management');
    }
}
