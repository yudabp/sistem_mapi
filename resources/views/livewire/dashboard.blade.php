<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Dashboard header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">📊 Ringkasan Utama</h1>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-8">
        <!-- Total Production Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-5 text-center hover:shadow-md transition-shadow duration-300">
            <div class="text-4xl mb-3">🌾</div>
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">Total Produksi</h3>
            <b class="text-xl text-gray-800 dark:text-gray-100">{{ number_format($totalProduction, 2) }} KG</b>
        </div>

        <!-- Total Sales Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-5 text-center hover:shadow-md transition-shadow duration-300">
            <div class="text-4xl mb-3">💰</div>
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">Total Penjualan</h3>
            <b class="text-xl text-gray-800 dark:text-gray-100">Rp {{ number_format($totalSales, 2, ',', '.') }}</b>
        </div>

        <!-- Total Income Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-5 text-center hover:shadow-md transition-shadow duration-300">
            <div class="text-4xl mb-3">📥</div>
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">Total Pemasukan</h3>
            <b class="text-xl text-gray-800 dark:text-gray-100">Rp {{ number_format($totalIncome, 2, ',', '.') }}</b>
        </div>

        <!-- Total Expenses Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-5 text-center hover:shadow-md transition-shadow duration-300">
            <div class="text-4xl mb-3">📤</div>
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">Total Pengeluaran</h3>
            <b class="text-xl text-gray-800 dark:text-gray-100">Rp {{ number_format($totalExpenses, 2, ',', '.') }}</b>
        </div>

        <!-- Total Debt Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-5 text-center hover:shadow-md transition-shadow duration-300">
            <div class="text-4xl mb-3">💳</div>
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">Total Hutang</h3>
            <b class="text-xl text-gray-800 dark:text-gray-100">Rp {{ number_format($totalDebt, 2, ',', '.') }}</b>
        </div>

        <!-- Total Employees Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-5 text-center hover:shadow-md transition-shadow duration-300">
            <div class="text-4xl mb-3">👥</div>
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">Jumlah Karyawan</h3>
            <b class="text-xl text-gray-800 dark:text-gray-100">{{ $totalEmployees }} Orang</b>
        </div>
    </div>

    <!-- Charts and Data Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Production Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm p-5">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Production Overview</h2>
            </div>
            <div class="h-80">
                <canvas id="productionChart"></canvas>
            </div>
        </div>

        <!-- Sales Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm p-5">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Sales Overview</h2>
            </div>
            <div class="h-80">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Recent Activities</h2>
        </header>
        <div class="p-3">
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <!-- Table header -->
                    <thead class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/30">
                        <tr>
                            <th class="p-2 whitespace-nowrap">
                                <div class="font-semibold text-left">Activity</div>
                            </th>
                            <th class="p-2 whitespace-nowrap">
                                <div class="font-semibold text-left">Date</div>
                            </th>
                            <th class="p-2 whitespace-nowrap">
                                <div class="font-semibold text-left">Amount</div>
                            </th>
                        </tr>
                    </thead>
                    <!-- Table body -->
                    <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                        <tr>
                            <td class="p-2 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="font-medium text-gray-800 dark:text-gray-100">Production Record</div>
                                </div>
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <div class="text-left">2025-01-15</div>
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <div class="text-left">5,000 KG</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="font-medium text-gray-800 dark:text-gray-100">Sales Record</div>
                                </div>
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <div class="text-left">2025-01-14</div>
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <div class="text-left">Rp 15,000,000</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="font-medium text-gray-800 dark:text-gray-100">Income Transaction</div>
                                </div>
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <div class="text-left">2025-01-13</div>
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <div class="text-left">Rp 25,000,000</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="font-medium text-gray-800 dark:text-gray-100">New Employee</div>
                                </div>
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <div class="text-left">2025-01-12</div>
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <div class="text-left">Rp 4,500,000</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Initialize charts when the component is updated
        document.addEventListener('livewire:initialized', () => {
            // Production Chart
            const productionCtx = document.getElementById('productionChart').getContext('2d');
            new Chart(productionCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Production (KG)',
                        data: [5000, 7500, 6000, 8000, 7000, 9000],
                        borderColor: 'rgb(99, 102, 241)',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Sales Chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            new Chart(salesCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Sales (Rp)',
                        data: [12000000, 18000000, 15000000, 22000000, 19000000, 25000000],
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</div>
