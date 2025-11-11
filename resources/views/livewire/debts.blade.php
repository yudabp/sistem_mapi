<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Page header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Data Hutang</h1>
    </div>

    <!-- Persistent Message -->
    @if($persistentMessage)
        <div class="mb-6">
            @switch($messageType)
                @case('success')
                    <div class="bg-emerald-50 text-emerald-700 p-4 rounded-lg dark:bg-emerald-500/10 dark:text-emerald-500 flex justify-between items-center">
                        <span>{{ $persistentMessage }}</span>
                        <button wire:click="clearPersistentMessage" class="text-emerald-700 dark:text-emerald-500 hover:text-emerald-900 dark:hover:text-emerald-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    @break

                @case('error')
                    <div class="bg-red-50 text-red-700 p-4 rounded-lg dark:bg-red-500/10 dark:text-red-500 flex justify-between items-center">
                        <span>{{ $persistentMessage }}</span>
                        <button wire:click="clearPersistentMessage" class="text-red-700 dark:text-red-500 hover:text-red-900 dark:hover:text-red-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    @break

                @case('warning')
                    <div class="bg-amber-50 text-amber-700 p-4 rounded-lg dark:bg-amber-500/10 dark:text-amber-500 flex justify-between items-center">
                        <span>{{ $persistentMessage }}</span>
                        <button wire:click="clearPersistentMessage" class="text-amber-700 dark:text-amber-500 hover:text-amber-900 dark:hover:text-amber-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    @break

                @case('info')
                    <div class="bg-blue-50 text-blue-700 p-4 rounded-lg dark:bg-blue-500/10 dark:text-blue-500 flex justify-between items-center">
                        <span>{{ $persistentMessage }}</span>
                        <button wire:click="clearPersistentMessage" class="text-blue-700 dark:text-blue-500 hover:text-blue-900 dark:hover:text-blue-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    @break

                @default
                    <div class="bg-gray-50 text-gray-700 p-4 rounded-lg dark:bg-gray-500/10 dark:text-gray-500 flex justify-between items-center">
                        <span>{{ $persistentMessage }}</span>
                        <button wire:click="clearPersistentMessage" class="text-gray-700 dark:text-gray-500 hover:text-gray-900 dark:hover:text-gray-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
            @endswitch
        </div>
    @endif

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm p-5">
            <div class="flex justify-between items-start">
                <div class="flex items-center">
                    <div class="bg-rose-100 dark:bg-rose-500/30 p-3 rounded-lg mr-4">
                        <svg class="w-6 h-6 fill-current text-rose-500 dark:text-rose-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 mb-1">Total Hutang</div>
                        <div class="flex items-baseline">
                            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">Rp {{ number_format($total_debt, 2, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm p-5">
            <div class="flex justify-between items-start">
                <div class="flex items-center">
                    <div class="bg-emerald-100 dark:bg-emerald-500/30 p-3 rounded-lg mr-4">
                        <svg class="w-6 h-6 fill-current text-emerald-500 dark:text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 mb-1">Jumlah Dibayar</div>
                        <div class="flex items-baseline">
                            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">Rp {{ number_format($total_paid_amount, 2, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm p-5">
            <div class="flex justify-between items-start">
                <div class="flex items-center">
                    <div class="bg-amber-100 dark:bg-amber-500/30 p-3 rounded-lg mr-4">
                        <svg class="w-6 h-6 fill-current text-amber-500 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M11.8 10.9c-2.27-.59-3.06-1.24-3.06-2.08 0-1.05 1.02-1.67 2.71-1.67 1.66 0 2.45.66 2.45 1.72 0 .76-.42 1.21-1.6 1.51l-1.56.38c-.7.16-.96.44-.96.87 0 .52.39.83 1.2.83.52 0 .96-.16 1.26-.41.39-.31.45-.53.45-1.26V7.59c0-.22.16-.33.33-.33h1.03c.21 0 .33.13.33.33v.33c0 1.1-.66 1.72-1.5 2.02l-1.54.38c-.94.23-1.5.61-1.5 1.35 0 .76.63 1.22 1.71 1.22 1.31 0 2.05-.55 2.05-1.55 0-.65-.31-1.06-.77-1.22.45-.6.71-1.35.71-2.05v-.33c0-.22-.16-.33-.33-.33h-.98c-.21 0-.33.13-.33.33v.33c0 .51.28.84.69.93l1.57.39c.7.19 1.05.56 1.05 1.19 0 1.11-1.04 1.71-2.71 1.71-1.65 0-2.5-.64-2.5-1.81 0-.79.4-1.26 1.65-1.56l1.54-.38c.72-.18.98-.44.98-.88 0-.52-.4-.86-1.11-.86-.55 0-1.15.18-1.41.42-.41.3-.45.57-.45 1.28v.33c0 .22.16.33.33.33h1.08c.21 0 .33-.13.33-.33v-.33c0-1.1.69-1.71 1.5-2.02l1.54-.38c.95-.23 1.5-.59 1.5-1.35 0-.77-.66-1.22-1.71-1.22-1.31 0-2.05.54-2.05 1.55 0 .64.31 1.07.74 1.24-.46.57-.74 1.31-.74 2.03v.33c0 .22.16.33.33.33h.98c.21 0 .33-.13.33-.33v-.33c0-.52-.3-.84-.7-.93l-1.57-.39c-.67-.19-1.05-.55-1.05-1.18z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 mb-1">Sisa Hutang</div>
                        <div class="flex items-baseline">
                            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">Rp {{ number_format($total_remaining_amount, 2, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section - Button to open modal -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-8">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">Registrasi Hutang</h2>
                <div class="flex flex-wrap gap-2">
                    <!-- Import button with dropdown -->
                    @canedit
                    <div class="relative group">
                        <button 
                            type="button"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                            Impor
                        </button>
                        <div class="absolute right-0 mt-0 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-10 hidden group-hover:block">
                            <button 
                                wire:click="openImportModal"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Unggah Excel
                            </button>
                            <a 
                                href="{{ route('debts.sample.download') }}"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Unduh Contoh
                            </a>
                        </div>
                    </div>
                    @endcanedit
                    
                    <div class="relative group">
                        <button 
                            type="button"
                            class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Ekspor
                        </button>
                        <div class="absolute right-0 mt-0 w-56 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-10 hidden group-hover:block">
                            <div class="px-4 py-2 text-xs font-medium text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                                Opsi Ekspor
                            </div>
                            <div class="px-4 py-2">
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai</label>
                                <input 
                                    type="text" 
                                    wire:model="exportStartDate"
                                    placeholder="DD-MM-YYYY"
                                    class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded shadow-sm focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 dark:bg-gray-700 dark:text-gray-300"
                                    x-data
                                    x-init="
                                        $el.addEventListener('input', function(e) {
                                            let input = e.target.value.replace(/\D/g, '');
                                            let formatted = '';
                                            
                                            if (input.length > 0) {
                                                formatted = input.substring(0, 2); // Day
                                                if (input.length >= 3) {
                                                    formatted += '-' + input.substring(2, 4); // Month
                                                    if (input.length >= 5) {
                                                        formatted += '-' + input.substring(4, 8); // Year
                                                    }
                                                }
                                            }
                                            
                                            $el.value = formatted;
                                        });
                                        
                                        $el.addEventListener('blur', function(e) {
                                            // Validate date format on blur
                                            let dateValue = e.target.value;
                                            let datePattern = /^(\d{2})-(\d{2})-(\d{4})$/;
                                            let match = dateValue.match(datePattern);
                                            
                                            if (match) {
                                                let day = parseInt(match[1]);
                                                let month = parseInt(match[2]);
                                                let year = parseInt(match[3]);
                                                
                                                // Basic validation
                                                if (day < 1 || day > 31 || month < 1 || month > 12) {
                                                    // You can add custom validation here
                                                }
                                            }
                                        });
                                    "
                                />
                            </div>
                            <div class="px-4 py-2">
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Akhir</label>
                                <input 
                                    type="text" 
                                    wire:model="exportEndDate"
                                    placeholder="DD-MM-YYYY"
                                    class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded shadow-sm focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 dark:bg-gray-700 dark:text-gray-300"
                                    x-data
                                    x-init="
                                        $el.addEventListener('input', function(e) {
                                            let input = e.target.value.replace(/\D/g, '');
                                            let formatted = '';
                                            
                                            if (input.length > 0) {
                                                formatted = input.substring(0, 2); // Day
                                                if (input.length >= 3) {
                                                    formatted += '-' + input.substring(2, 4); // Month
                                                    if (input.length >= 5) {
                                                        formatted += '-' + input.substring(4, 8); // Year
                                                    }
                                                }
                                            }
                                            
                                            $el.value = formatted;
                                        });
                                        
                                        $el.addEventListener('blur', function(e) {
                                            // Validate date format on blur
                                            let dateValue = e.target.value;
                                            let datePattern = /^(\d{2})-(\d{2})-(\d{4})$/;
                                            let match = dateValue.match(datePattern);
                                            
                                            if (match) {
                                                let day = parseInt(match[1]);
                                                let month = parseInt(match[2]);
                                                let year = parseInt(match[3]);
                                                
                                                // Basic validation
                                                if (day < 1 || day > 31 || month < 1 || month > 12) {
                                                    // You can add custom validation here
                                                }
                                            }
                                        });
                                    "
                                />
                            </div>
                            <button 
                                wire:click="exportToExcel"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Ekspor ke Excel
                            </button>
                            <button 
                                wire:click="exportToPdf"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Ekspor ke PDF
                            </button>
                        </div>
                    </div>
                    
                    <!-- <button 
                        wire:click="openCreateModal"
                        class="px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors flex items-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Debt Record
                    </button> -->
                    @canedit
                    <button
                        wire:click="openCreateModal"
                        class="px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors flex items-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Data
                    </button>
                @else
                    <!-- <div class="px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Data
                    </div> -->
                @endcanedit
                </div>
            </div>
        </header>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-8 p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pencarian</label>
            <input 
                type="text" 
                wire:model.live="search"
                placeholder="Cari berdasarkan pemberi hutang atau keterangan"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
            />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter berdasarkan Status</label>
            <select 
                wire:model.live="statusFilter"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
            >
                <option value="">Semua Status</option>
                <option value="unpaid">Belum Lunas</option>
                <option value="paid">Lunas</option>
            </select>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Data Hutang</h2>
        </header>
        <div class="p-3">
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/30">
                            <th class="p-2 whitespace-nowrap">Pemberi Hutang</th>
                            <th class="p-2 whitespace-nowrap">Jumlah</th>
                            <th class="p-2 whitespace-nowrap">Sisa Hutang</th>
                            <th class="p-2 whitespace-nowrap">Tanggal Jatuh Tempo</th>
                            <th class="p-2 whitespace-nowrap">Status</th>
                            <th class="p-2 whitespace-nowrap">Bukti</th>
                            @canedit
                            <th class="p-2 whitespace-nowrap">Aksi</th>
                            @else
                            <!-- <th class="p-2 whitespace-nowrap">--</th> -->
                            @endcanedit
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse($debts as $debt)
                            <tr>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left font-medium text-gray-800 dark:text-gray-100">{{ $debt->creditor }}</div>
                                    @if($debt->debt_type_id == 3 && $debt->employee)
                                        <div class="text-xs text-violet-600 dark:text-violet-400 font-medium">
                                            <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $debt->employee->name }} ({{ $debt->employee->ndp }})
                                        </div>
                                    @endif
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $debt->description }}</div>
                                    @if($debt->debtType)
                                        <div class="text-xs text-amber-600 dark:text-amber-400">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 dark:bg-amber-900/30">
                                                {{ $debt->debtType->name }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left font-medium text-gray-800 dark:text-gray-100">Rp {{ number_format($debt->amount, 2, ',', '.') }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left font-medium {{ $debt->remaining_debt > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                        Rp {{ number_format($debt->remaining_debt, 2, ',', '.') }}
                                    </div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">{{ $debt->due_date->format('d M Y') }}</div>
                                    @if($debt->paid_date && $debt->status == 'paid')
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Dibayar pada: {{ $debt->paid_date->format('d M Y') }}</div>
                                    @endif
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $debt->status === 'unpaid' ? 'bg-amber-100 text-amber-800 dark:bg-amber-800/30 dark:text-amber-500' : 
                                           'bg-emerald-100 text-emerald-800 dark:bg-emerald-800/30 dark:text-emerald-500' }}">
                                        {{ $debt->status === 'unpaid' ? 'Belum Lunas' : 'Lunas' }}
                                    </span>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    @if($debt->proof_document_path)
                                        <button 
                                            wire:click="showPhoto('{{ $debt->proof_document_path }}')"
                                            class="text-blue-600 hover:underline dark:text-blue-400"
                                        >
                                            Lihat Dokumen
                                        </button>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">Tidak ada bukti</span>
                                    @endif
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    @if($debt->status === 'unpaid')
                                        <!-- <button 
                                            wire:click="markAsPaid({{ $debt->id }})"
                                            class="px-3 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-700 text-sm mr-2"
                                        >
                                            Tandai Lunas
                                        </button> -->
                                    @endif
                                    <div class="flex space-x-2">
                                        @canedit
                                        <button 
                                            wire:click="openEditModal({{ $debt->id }})"
                                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm"
                                        >
                                            Edit
                                        </button>
                                        <button 
                                            wire:click="confirmDelete({{ $debt->id }}, '{{ $debt->creditor }}')"
                                            class="px-3 py-1 bg-rose-600 text-white rounded hover:bg-rose-700 text-sm"
                                        >
                                            Hapus
                                        </button>
                                        @else
                                        <!-- <div class="px-3 py-1 bg-gray-400 text-white rounded cursor-not-allowed text-sm">
                                            --
                                        </div> -->
                                        @endcanedit
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-2 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada data hutang ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Debt Data Modal -->
    <x-dialog-modal wire:model.live="showModal" maxWidth="2xl">
        <x-slot name="title">
            {{ $isEditing ? 'Edit Debt Record' : 'Add Debt Record' }}
        </x-slot>

        <x-slot name="content">
            <!-- Persistent Message inside modal -->
            @if($persistentMessage)
                <div class="mb-4">
                    <div class="bg-{{ $messageType === 'error' ? 'red' : 'emerald' }}-50 text-{{ $messageType === 'error' ? 'red' : 'emerald' }}-700 p-3 rounded-lg dark:bg-{{ $messageType === 'error' ? 'red' : 'emerald' }}-500/10 dark:text-{{ $messageType === 'error' ? 'red' : 'emerald' }}-500 flex justify-between items-center">
                        <span>{{ $persistentMessage }}</span>
                        <button wire:click="clearPersistentMessage" class="text-{{ $messageType === 'error' ? 'red' : 'emerald' }}-700 dark:text-{{ $messageType === 'error' ? 'red' : 'emerald' }}-500 hover:text-{{ $messageType === 'error' ? 'red' : 'emerald' }}-900 dark:hover:text-{{ $messageType === 'error' ? 'red' : 'emerald' }}-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
            
            <form wire:submit.prevent="saveDebtModal" class="space-y-6">
                <!-- Required Data Section -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Data Wajib
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="amount">
                                Jumlah Hutang (Rp)
                            </label>
                            <input
                                id="amount"
                                class="w-full px-3 py-2 border {{ $errors->has('amount') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                                type="number"
                                step="0.01"
                                wire:model="amount"
                                placeholder="Masukkan jumlah hutang"
                            />
                            @error('amount')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Creditor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="creditor">
                                Pemberi Hutang
                            </label>
                            <div x-data="{
                                readOnly: false,
                                init() {
                                    const updateReadOnly = (value) => {
                                        this.readOnly = String(value) === '3';
                                    };
                                    $watch('$wire.debt_type_id', updateReadOnly);
                                    updateReadOnly(@entangle('debt_type_id'));
                                }
                            }">
                                <input
                                    id="creditor"
                                    class="w-full px-3 py-2 border {{ $errors->has('creditor') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300 {{ $debt_type_id == 3 ? 'bg-gray-50 dark:bg-gray-600' : '' }}"
                                    type="text"
                                    wire:model="creditor"
                                    placeholder="{{ $debt_type_id == 3 ? 'Nama karyawan akan terisi otomatis' : 'Masukkan nama pemberi hutang' }}"
                                    :readonly="readOnly"
                                    :class="{ 'bg-gray-100 dark:bg-gray-700 cursor-not-allowed': readOnly }"
                                />
                                <p x-show="readOnly" class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Nama karyawan akan terisi otomatis saat memilih karyawan
                                </p>
                            </div>
                            @error('creditor')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Debt Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="debt_type_id">
                                Jenis Hutang
                            </label>
                            <select
                                id="debt_type_id"
                                class="w-full px-3 py-2 border {{ $errors->has('debt_type_id') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                                wire:model="debt_type_id"
                                x-data
                                x-on:change="$dispatch('debt-type-changed', { value: $el.value })"
                            >
                                <option value="">Pilih Jenis Hutang</option>
                                @foreach($debtTypes as $debtType)
                                    <option value="{{ $debtType->id }}">{{ $debtType->name }}</option>
                                @endforeach
                            </select>
                            @error('debt_type_id')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Optional Data Section -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Data Opsional
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Due Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="due_date">
                                Tanggal Jatuh Tempo (DD-MM-YYYY)
                            </label>
                            <input
                                id="due_date"
                                class="w-full px-3 py-2 border {{ $errors->has('due_date') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                                type="text"
                                placeholder="DD-MM-YYYY"
                                wire:model="due_date"
                                x-data
                                x-init="
                                    $el.addEventListener('input', function(e) {
                                        let input = e.target.value.replace(/\D/g, '');
                                        let formatted = '';

                                        if (input.length > 0) {
                                            formatted = input.substring(0, 2); // Day
                                            if (input.length >= 3) {
                                                formatted += '-' + input.substring(2, 4); // Month
                                                if (input.length >= 5) {
                                                    formatted += '-' + input.substring(4, 8); // Year
                                                }
                                            }
                                        }

                                        $el.value = formatted;
                                    });

                                    $el.addEventListener('blur', function(e) {
                                        // Validate date format on blur
                                        let dateValue = e.target.value;
                                        let datePattern = /^(\d{2})-(\d{2})-(\d{4})$/;
                                        let match = dateValue.match(datePattern);

                                        if (match) {
                                            let day = parseInt(match[1]);
                                            let month = parseInt(match[2]);
                                            let year = parseInt(match[3]);

                                            // Basic validation
                                            if (day < 1 || day > 31 || month < 1 || month > 12) {
                                                // You can add custom validation here
                                            }
                                        }
                                    });
                                "
                            />
                            @error('due_date')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Employee Selection - Only show for Hutang Gaji Karyawan -->
                        <div x-data="{
                            showEmployee: false,
                            init() {
                                $watch('$wire.debt_type_id', (value) => {
                                    this.showEmployee = value == '3';
                                });
                                this.showEmployee = @entangle('debt_type_id') == '3';
                            }
                        }" x-show="showEmployee" x-transition>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="employee_id">
                                Nama Karyawan <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="employee_id"
                                class="w-full px-3 py-2 border {{ $errors->has('employee_id') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                                wire:model="employee_id"
                            >
                                <option value="">Pilih Karyawan</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->ndp }})</option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Cicilan per Bulan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="cicilan_per_bulan">
                                Cicilan per Bulan
                            </label>
                            <input
                                id="cicilan_per_bulan"
                                class="w-full px-3 py-2 border {{ $errors->has('cicilan_per_bulan') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                                type="number"
                                step="0.01"
                                wire:model="cicilan_per_bulan"
                                placeholder="Masukkan cicilan per bulan"
                            />
                            @error('cicilan_per_bulan')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="description">
                                Keterangan
                            </label>
                            <textarea
                                id="description"
                                class="w-full px-3 py-2 border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                                wire:model="description"
                                placeholder="Masukkan keterangan detail hutang"
                                rows="2"
                            ></textarea>
                            @error('description')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Proof Document -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="proof_document">
                                Bukti Dokumen
                            </label>
                            <input
                                id="proof_document"
                                type="file"
                                wire:model.lazy="proof_document"
                                class="w-full px-3 py-2 border {{ $errors->has('proof_document') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                            />
                            @if($isEditing && $proof_document === null)
                                <div class="mt-2">
                                    <small class="text-gray-500">Biarkan kosong untuk menyimpan dokumen yang ada</small>
                                </div>
                            @endif
                            @error('proof_document')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeCreateModal" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <button
                wire:click="saveDebtModal"
                wire:loading.attr="disabled"
                class="ms-3 px-4 py-2 bg-violet-600 text-white hover:bg-violet-700 rounded-lg font-medium transition-colors"
            >
                {{ $isEditing ? 'Perbarui' : 'Simpan' }} Data Hutang
            </button>
        </x-slot>
    </x-dialog-modal>

    <!-- Delete Confirmation Modal -->
    <x-confirmation-modal wire:model.live="showDeleteConfirmation">
        <x-slot name="title">
            {{ __('Hapus Data Hutang') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Apakah Anda yakin ingin menghapus data hutang ":name"?', ['name' => $deletingDebtName]) }}
            {{ __('Setelah catatan dihapus, semua datanya akan dihapus secara permanen.') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeDeleteConfirmation" wire:loading.attr="disabled">
                {{ __('Batal') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteDebtConfirmed" wire:loading.attr="disabled">
                {{ __('Hapus Hutang') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- Photo Preview Modal -->
    <x-dialog-modal wire:model.live="showPhotoModal" maxWidth="2xl">
        <x-slot name="title">
            Preview Dokumen Hutang
        </x-slot>

        <x-slot name="content">
            @if($photoToView)
                <div class="flex justify-center">
                    <img src="{{ asset('storage/' . $photoToView) }}" alt="Dokumen Hutang" class="max-w-full h-auto rounded-lg shadow-md">
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closePhotoModal" wire:loading.attr="disabled">
                {{ __('Close') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Import Modal -->
    <x-dialog-modal wire:model.live="showImportModal" maxWidth="2xl">
        <x-slot name="title">
            Impor Data Hutang dari Excel
        </x-slot>

        <x-slot name="content">
            <div class="mb-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Unggah file Excel untuk mengimpor data hutang. File harus mengandung kolom-kolom berikut:
                </p>
                <ul class="mt-2 text-sm text-gray-600 dark:text-gray-400 list-disc list-inside">
                    <li><strong>amount</strong> - Jumlah hutang</li>
                    <li><strong>creditor</strong> - Nama pemberi hutang</li>
                    <li><strong>due_date</strong> - Tanggal jatuh tempo</li>
                    <li><strong>description</strong> - Keterangan hutang</li>
                    <li><strong>status</strong> - Status: unpaid (belum lunas) atau paid (lunas)</li>
                    <li><strong>paid_date</strong> - Tanggal pembayaran</li>
                </ul>
            </div>

            <form wire:submit.prevent="importDebt" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="importFile">
                        File Excel
                    </label>
                    <input
                        id="importFile"
                        type="file"
                        wire:model="importFile"
                        accept=".xlsx,.xls,.csv"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                    />
                    @error('importFile')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="text-xs text-gray-500 dark:text-gray-400">
                    Format yang didukung: .xlsx, .xls, .csv
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeImportModal" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="importDebt" wire:loading.attr="disabled">
                Impor Data
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
