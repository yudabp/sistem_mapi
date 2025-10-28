<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto"
     x-data="{ chartInitialized: false }"
     x-init="$watch('$wire.dateRange', () => {
         if (typeof window.destroyAllCharts === 'function') {
             window.destroyAllCharts();
         }
         $nextTick(() => {
             if (typeof window.initializeCharts === 'function') {
                 setTimeout(() => window.initializeCharts(), 100);
             }
         });
     });
     $nextTick(() => {
         if (!chartInitialized && typeof window.initializeCharts === 'function') {
             window.initializeCharts();
             chartInitialized = true;
         }
     })">
    <!-- Dashboard header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">📊 Ringkasan Utama</h1>

        <!-- Date Range Filter -->
        <div class="mt-4 sm:mt-0 flex items-center space-x-2">
            <select wire:model="dateRange" wire:change="updateDateRange" class="text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                <option value="this_week">Minggu Ini</option>
                <option value="last_week">Minggu Lalu</option>
                <option value="this_month">Bulan Ini</option>
                <option value="last_month">Bulan Lalu</option>
                <option value="this_quarter">Kuartal Ini</option>
                <option value="this_year">Tahun Ini</option>
            </select>
            <div class="text-sm text-gray-500 dark:text-gray-400" wire:loading wire:target="dateRange">
                <span class="inline-flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memuat...
                </span>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400" wire:loading.remove wire:target="dateRange">
                {{ $startDate }} - {{ $endDate }}
            </div>
        </div>
    </div>

    <!-- Period indicator -->
    <div class="mb-6 text-center">
        <span class="inline-flex items-center px-4 py-2 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-full text-sm font-semibold">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Periode: {{ Carbon\Carbon::parse($startDate)->locale('id')->translatedFormat('d F Y') }} - {{ Carbon\Carbon::parse($endDate)->locale('id')->translatedFormat('d F Y') }}
        </span>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Production Card -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-gray-800 dark:to-gray-700 rounded-xl border border-green-200 dark:border-gray-600 shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Produksi (Periode)</h3>
                    <b class="text-2xl text-gray-800 dark:text-gray-100">{{ number_format($totalProduction, 2) }} KG</b>
                </div>
                <div class="text-5xl">🌾</div>
            </div>
            <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                <span class="inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    +12% dari bulan lalu
                </span>
            </div>
        </div>

        <!-- Total Sales Card -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-gray-800 dark:to-gray-700 rounded-xl border border-blue-200 dark:border-gray-600 shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Penjualan (Periode)</h3>
                    <b class="text-2xl text-gray-800 dark:text-gray-100">Rp {{ number_format($totalSales, 0, ',', '.') }}</b>
                </div>
                <div class="text-5xl">💰</div>
            </div>
            <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                <span class="inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    +8% dari bulan lalu
                </span>
            </div>
        </div>

        <!-- Total Income Card -->
        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-gray-800 dark:to-gray-700 rounded-xl border border-emerald-200 dark:border-gray-600 shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Pemasukan (Periode)</h3>
                    <b class="text-2xl text-gray-800 dark:text-gray-100">Rp {{ number_format($totalIncome, 0, ',', '.') }}</b>
                </div>
                <div class="text-5xl">📥</div>
            </div>
            <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                <span class="inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    +15% dari bulan lalu
                </span>
            </div>
        </div>

        <!-- Total Expenses Card -->
        <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-gray-800 dark:to-gray-700 rounded-xl border border-red-200 dark:border-gray-600 shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Pengeluaran (Periode)</h3>
                    <b class="text-2xl text-gray-800 dark:text-gray-100">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</b>
                </div>
                <div class="text-5xl">📤</div>
            </div>
            <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                <span class="inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                    -5% dari bulan lalu
                </span>
            </div>
        </div>

        <!-- Total Debt Card -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-gray-800 dark:to-gray-700 rounded-xl border border-orange-200 dark:border-gray-600 shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Total Hutang</h3>
                    <b class="text-2xl text-gray-800 dark:text-gray-100">Rp {{ number_format($totalDebt, 0, ',', '.') }}</b>
                </div>
                <div class="text-5xl">💳</div>
            </div>
            <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                <span class="inline-flex items-center text-orange-600">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ \App\Models\Debt::where('status', 'unpaid')->count() }} hutang aktif
                </span>
            </div>
        </div>

        <!-- Total Employees Card -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-gray-800 dark:to-gray-700 rounded-xl border border-purple-200 dark:border-gray-600 shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Jumlah Karyawan</h3>
                    <b class="text-2xl text-gray-800 dark:text-gray-100">{{ $totalEmployees }} Orang</b>
                </div>
                <div class="text-5xl">👥</div>
            </div>
            <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                <span class="inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    {{ \App\Models\Employee::where('status', 'aktif')->count() }} aktif
                </span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="space-y-6 mb-8">
        <!-- First Row: Production Trends & Sales vs Production -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Production Trends Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">📈 Tren Produksi (6 Bulan Terakhir)</h2>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Grafik produksi TBS dan olahan
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="productionTrendsChart" style="width: 100%; height: 100%;"></canvas>
                </div>
            </div>

            <!-- Sales vs Production Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">⚖️ Penjualan vs Produksi</h2>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors" id="chartTypeLine">Line</button>
                        <button class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors" id="chartTypeBar">Bar</button>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="salesProductionChart" style="width: 100%; height: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- Second Row: Financial Flow & Debt Aging -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Financial Flow Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">💰 Arus Keuangan (KP & BKK)</h2>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Total: Rp {{ number_format($totalIncome - $totalExpenses, 0, ',', '.') }}</span>
                </div>
                <div class="h-80">
                    <canvas id="financialFlowChart" style="width: 100%; height: 100%;"></canvas>
                </div>
            </div>

            <!-- Debt Aging Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">💳 Umur Hutang</h2>
                    <span class="text-sm text-orange-600 font-semibold">{{ \App\Models\Debt::where('status', 'unpaid')->sum('amount') > 0 ? number_format(\App\Models\Debt::where('status', 'unpaid')->sum('amount'), 0, ',', '.') : 0 }}</span>
                </div>
                <div class="h-80">
                    <canvas id="debtAgingChart" style="width: 100%; height: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- Third Row: Employee Distribution & Department Breakdown -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Employee Distribution by Department -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">👥 Distribusi Karyawan</h2>
                </div>
                <div class="h-64">
                    <canvas id="employeeDistributionChart" style="width: 100%; height: 100%;"></canvas>
                </div>
            </div>

            <!-- Top 5 Production by Division -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">🏆 Top 5 Produksi per Afdeling</h2>
                </div>
                <div class="h-64">
                    <canvas id="topProductionChart" style="width: 100%; height: 100%;"></canvas>
                </div>
            </div>

            <!-- Monthly Profit Margin -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">📊 Margin Laba Bulanan</h2>
                </div>
                <div class="h-64">
                    <canvas id="profitMarginChart" style="width: 100%; height: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pass data to JavaScript -->
    <script>
        window.chartData = {!! json_encode([
            'productionData' => $productionData,
            'salesData' => $salesData,
            'financialData' => $financialData,
            'debtAgingData' => $debtAgingData,
            'employeeDistributionData' => $employeeDistributionData,
            'topDivisions' => $topDivisions->toArray(),
            'profitMarginData' => $profitMarginData,
        ]) !!};
    </script>
</div>
