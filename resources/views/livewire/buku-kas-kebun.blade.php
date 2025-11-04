<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Page header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Buku Kas Kebun (BKK)</h1>
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
                    <div class="bg-emerald-100 dark:bg-emerald-500/30 p-3 rounded-lg mr-4">
                        <svg class="w-6 h-6 fill-current text-emerald-500 dark:text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 mb-1 flex justify-between items-center">
                            <span>Total Pemasukan</span>
                            <select 
                                wire:model.live="metricFilter" 
                                class="text-xs bg-transparent border-0 focus:ring-0 dark:text-gray-500"
                            >
                                <option value="all">Total Semua</option>
                                <option value="today">Hari ini</option>
                                <option value="yesterday">Kemarin</option>
                                <option value="this_week">Minggu ini</option>
                                <option value="last_week">Minggu kemarin</option>
                                <option value="this_month">Bulan ini</option>
                                <option value="last_month">Bulan kemarin</option>
                                <option value="custom">Custom tanggal</option>
                            </select>
                        </div>
                        <div class="flex items-baseline">
                            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">Rp {{ number_format($total_income, 2, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Custom date range inputs when 'custom' filter is selected -->
            @if($metricFilter === 'custom')
            <div class="mt-4 grid grid-cols-2 gap-2">
                <div>
                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Tanggal awal</label>
                    <input 
                        type="text" 
                        wire:model.live="startDate"
                        placeholder="DD-MM-YYYY"
                        class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded shadow-sm focus:outline-none focus:ring-1 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
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
                <div>
                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Tanggal akhir</label>
                    <input 
                        type="text" 
                        wire:model.live="endDate"
                        placeholder="DD-MM-YYYY"
                        class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded shadow-sm focus:outline-none focus:ring-1 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
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
            </div>
            @endif
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
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 mb-1 flex justify-between items-center">
                            <span>Total Pengeluaran</span>
                            <select 
                                wire:model.live="metricFilter" 
                                class="text-xs bg-transparent border-0 focus:ring-0 dark:text-gray-500"
                            >
                                <option value="all">Total Semua</option>
                                <option value="today">Hari ini</option>
                                <option value="yesterday">Kemarin</option>
                                <option value="this_week">Minggu ini</option>
                                <option value="last_week">Minggu kemarin</option>
                                <option value="this_month">Bulan ini</option>
                                <option value="last_month">Bulan kemarin</option>
                                <option value="custom">Custom tanggal</option>
                            </select>
                        </div>
                        <div class="flex items-baseline">
                            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">Rp {{ number_format($total_expenses, 2, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Custom date range inputs when 'custom' filter is selected -->
            @if($metricFilter === 'custom')
            <div class="mt-4 grid grid-cols-2 gap-2">
                <div>
                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Tanggal awal</label>
                    <input 
                        type="text" 
                        wire:model.live="startDate"
                        placeholder="DD-MM-YYYY"
                        class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded shadow-sm focus:outline-none focus:ring-1 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
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
                <div>
                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Tanggal akhir</label>
                    <input 
                        type="text" 
                        wire:model.live="endDate"
                        placeholder="DD-MM-YYYY"
                        class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded shadow-sm focus:outline-none focus:ring-1 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
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
            </div>
            @endif
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm p-5">
            <div class="flex justify-between items-start">
                <div class="flex items-center">
                    <div class="bg-violet-100 dark:bg-violet-500/30 p-3 rounded-lg mr-4">
                        <svg class="w-6 h-6 fill-current text-violet-500 dark:text-violet-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M11.8 10.9c-2.27-.59-3.06-1.24-3.06-2.08 0-1.05 1.02-1.67 2.71-1.67 1.66 0 2.45.66 2.45 1.72 0 .76-.42 1.21-1.6 1.51l-1.56.38c-.7.16-.96.44-.96.87 0 .52.39.83 1.2.83.52 0 .96-.16 1.26-.41.39-.31.45-.53.45-1.26V7.59c0-.22.16-.33.33-.33h1.03c.21 0 .33.13.33.33v.33c0 1.1-.66 1.72-1.5 2.02l-1.54.38c-.94.23-1.5.61-1.5 1.35 0 .76.63 1.22 1.71 1.22 1.31 0 2.05-.55 2.05-1.55 0-.65-.31-1.06-.77-1.22.45-.6.71-1.35.71-2.05v-.33c0-.22-.16-.33-.33-.33h-.98c-.21 0-.33.13-.33-.33v-.33c0-.52-.3-.84-.7-.93l-1.57-.39c-.67-.19-1.05-.55-1.05-1.18z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 mb-1 flex justify-between items-center">
                            <span>Saldo</span>
                            <select 
                                wire:model.live="metricFilter" 
                                class="text-xs bg-transparent border-0 focus:ring-0 dark:text-gray-500"
                            >
                                <option value="all">Total Semua</option>
                                <option value="today">Hari ini</option>
                                <option value="yesterday">Kemarin</option>
                                <option value="this_week">Minggu ini</option>
                                <option value="last_week">Minggu kemarin</option>
                                <option value="this_month">Bulan ini</option>
                                <option value="last_month">Bulan kemarin</option>
                                <option value="custom">Custom tanggal</option>
                            </select>
                        </div>
                        <div class="flex items-baseline">
                            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">Rp {{ number_format($balance, 2, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Custom date range inputs when 'custom' filter is selected -->
            @if($metricFilter === 'custom')
            <div class="mt-4 grid grid-cols-2 gap-2">
                <div>
                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Tanggal awal</label>
                    <input 
                        type="text" 
                        wire:model.live="startDate"
                        placeholder="DD-MM-YYYY"
                        class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded shadow-sm focus:outline-none focus:ring-1 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
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
                <div>
                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Tanggal akhir</label>
                    <input 
                        type="text" 
                        wire:model.live="endDate"
                        placeholder="DD-MM-YYYY"
                        class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded shadow-sm focus:outline-none focus:ring-1 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
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
            </div>
            @endif
        </div>
    </div>

    <!-- Form Section - Buttons to open modal and import/export -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-8">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">Input Transaksi Buku Kas Kebun</h2>
                <div class="flex flex-wrap gap-2">
                    @canedit
                    <!-- Import button with dropdown -->
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
                            <button 
                                wire:click="downloadSampleExcel"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Unduh Contoh
                            </button>
                        </div>
                    </div>
                    @endcanedit
                    
                    <!-- Export button with dropdown -->
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
        </header>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-8 p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari</label>
            <input 
                type="text" 
                wire:model.live="search"
                placeholder="Cari berdasarkan sumber, tujuan, atau kategori"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
            />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter berdasarkan Tanggal</label>
            <input 
                type="month" 
                wire:model.live="dateFilter"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
            />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter berdasarkan Jenis</label>
            <select 
                wire:model.live="typeFilter"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
            >
                <option value="">Semua Jenis</option>
                <option value="income">Pemasukan</option>
                <option value="expense">Pengeluaran</option>
            </select>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Transaksi Buku Kas Kebun</h2>
        </header>
        <div class="p-3">
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/30">
                            <th class="p-2 whitespace-nowrap">Tanggal</th>
                            <th class="p-2 whitespace-nowrap">No. Transaksi</th>
                            <th class="p-2 whitespace-nowrap">Jenis</th>
                            <th class="p-2 whitespace-nowrap">Sumber/Tujuan</th>
                            <th class="p-2 whitespace-nowrap">Jumlah</th>
                            <th class="p-2 whitespace-nowrap">Kategori</th>
                            <th class="p-2 whitespace-nowrap">Referensi KP</th>
                            <th class="p-2 whitespace-nowrap">Bukti</th>
                            <th class="p-2 whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse($transactions as $transaction)
                            <tr id="transaction-{{ $transaction->id }}">
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">{{ $transaction->transaction_date->format('d M Y') }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left font-medium text-gray-800 dark:text-gray-100">
                                        {{ $transaction->transaction_number }}
                                        @if($this->isAutoGeneratedBkk($transaction))
                                            <span class="ml-2 px-2 py-1 text-xs bg-emerald-100 text-emerald-800 dark:bg-emerald-800/30 dark:text-emerald-500 rounded-full">
                                                Auto
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $transaction->transaction_type === 'income' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-800/30 dark:text-emerald-500' : 
                                           'bg-rose-100 text-rose-800 dark:bg-rose-800/30 dark:text-rose-500' }}">
                                        {{ ucfirst($transaction->transaction_type) }}
                                    </span>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">{{ $transaction->source_destination }}</div>
                                    @if($transaction->received_by)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Received by: {{ $transaction->received_by }}</div>
                                    @endif
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left font-medium {{ $transaction->transaction_type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                        {{ $transaction->transaction_type === 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 2, ',', '.') }}
                                    </div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">{{ $transaction->category }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    @if($transaction->kp_id && $transaction->keuanganPerusahaan)
                                        <div class="text-left">
                                            <span class="text-xs text-blue-600 dark:text-blue-400">
                                                {{ $transaction->keuanganPerusahaan->transaction_number }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    @if($transaction->proof_document_path)
                                        <button 
                                            wire:click="showPhoto('{{ $transaction->proof_document_path }}')"
                                            class="text-blue-600 hover:underline dark:text-blue-400"
                                        >
                                            Lihat Dokumen
                                        </button>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">Tidak ada bukti</span>
                                    @endif
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        @if($transaction->kp_id && $transaction->keuanganPerusahaan)
                                            <button 
                                                wire:click="showRelatedKpTransaction({{ $transaction->id }})"
                                                class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm"
                                                title="View related KP transaction"
                                            >
                                                View KP
                                            </button>
                                        @endif
                                        @canedit
                                        <button 
                                            wire:click="openEditModal({{ $transaction->id }})"
                                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm"
                                        >
                                            Ubah
                                        </button>
                                        <button 
                                            wire:click="confirmDelete({{ $transaction->id }}, '{{ $transaction->transaction_number }}')"
                                            class="px-3 py-1 bg-rose-600 text-white rounded hover:bg-rose-700 text-sm"
                                        >
                                            Hapus
                                        </button>
                                        @else
                                        <!-- <button 
                                            class="px-3 py-1 bg-gray-400 text-white rounded cursor-not-allowed text-sm"
                                            title="You do not have permission to edit"
                                        >
                                            Ubah
                                        </button>
                                        <button 
                                            class="px-3 py-1 bg-gray-400 text-white rounded cursor-not-allowed text-sm"
                                            title="You do not have permission to delete"
                                        >
                                            Hapus
                                        </button> -->
                                        @endcanedit
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-2 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada transaksi buku kas kebun ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Transaction Data Modal -->
    <x-dialog-modal wire:model.live="showModal" maxWidth="2xl">
        <x-slot name="title">
            {{ $isEditing ? 'Edit Buku Kas Kebun Transaction' : 'Add Buku Kas Kebun Transaction' }}
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
            
            <form wire:submit.prevent="saveTransactionModal" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Transaction Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="transaction_date">
                        Tanggal Transaksi (DD-MM-YYYY)
                    </label>
                    <input 
                        id="transaction_date"
                        class="w-full px-3 py-2 border {{ $errors->has('transaction_date') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        placeholder="DD-MM-YYYY"
                        wire:model="transaction_date"
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
                    @error('transaction_date') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Jenis Transaksi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="transaction_type">
                        Jenis Transaksi
                    </label>
                    <select 
                        id="transaction_type"
                        class="w-full px-3 py-2 border {{ $errors->has('transaction_type') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        wire:model="transaction_type"
                    >
                        <option value="income">Pemasukan</option>
                        <option value="expense">Pengeluaran</option>
                    </select>
                    @error('transaction_type')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Debt Payment Section - Show only for expense transactions -->
                @if($transaction_type === 'expense')
                <div class="md:col-span-2">
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-4">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Bayar Hutang (Opsional)
                            </label>
                            <button
                                type="button"
                                wire:click="toggleDebtPayment"
                                class="px-3 py-1 text-xs {{ $is_debt_payment ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-gray-600 hover:bg-gray-700' }} text-white rounded transition-colors"
                            >
                                {{ $is_debt_payment ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </div>

                        @if($is_debt_payment)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Debt Autocomplete -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Cari Hutang
                                </label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        wire:model.live="debt_search"
                                        wire:keydown.enter.prevent="closeDebtSuggestions"
                                        placeholder="Ketik nama kreditur atau deskripsi hutang..."
                                        class="w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                                    />
                                    @if($debt_search)
                                    <button
                                        type="button"
                                        wire:click="clearDebtSelection"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                    @endif
                                </div>

                                <!-- Suggestions Dropdown -->
                                @if($show_debt_suggestions && $debt_suggestions->count() > 0)
                                <div class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-64 overflow-y-auto">
                                    @foreach($debt_suggestions as $debt)
                                    <button
                                        type="button"
                                        wire:click="selectDebt({{ $debt->id }})"
                                        class="w-full px-3 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700 last:border-b-0"
                                    >
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="font-medium text-gray-800 dark:text-gray-200">{{ $debt->creditor }}</div>
                                                <div class="text-xs text-gray-600 dark:text-gray-400 truncate max-w-xs">{{ $debt->description }}</div>
                                            </div>
                                            <div class="text-right ml-4">
                                                <div class="text-sm font-semibold text-rose-600 dark:text-rose-400">
                                                    Rp {{ number_format($debt->sisa_hutang, 2, ',', '.') }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($debt->due_date)->format('d M Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                    @endforeach
                                </div>
                                @endif

                                <!-- Selected Debt Info -->
                                @if($selected_debt)
                                <div class="mt-3 p-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg">
                                    <div class="text-sm">
                                        <div class="font-medium text-emerald-800 dark:text-emerald-300 mb-2">Hutang Terpilih:</div>
                                        <div class="grid grid-cols-2 gap-2 text-xs">
                                            <div>
                                                <span class="text-gray-600 dark:text-gray-400">Kreditur:</span>
                                                <span class="font-medium">{{ $selected_debt->creditor }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-600 dark:text-gray-400">Total Hutang:</span>
                                                <span class="font-medium">Rp {{ number_format($selected_debt->amount, 2, ',', '.') }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-600 dark:text-gray-400">Sisa Hutang:</span>
                                                <span class="font-medium text-emerald-600 dark:text-emerald-400">Rp {{ number_format($selected_debt->sisa_hutang, 2, ',', '.') }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-600 dark:text-gray-400">Jatuh Tempo:</span>
                                                <span class="font-medium">{{ \Carbon\Carbon::parse($selected_debt->due_date)->format('d M Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Payment Method -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Metode Pembayaran
                                </label>
                                <select
                                    wire:model="payment_method"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                                >
                                    <option value="">Pilih metode</option>
                                    <option value="Cash">Tunai</option>
                                    <option value="Transfer">Transfer Bank</option>
                                    <option value="Cheque">Cek</option>
                                    <option value="Other">Lainnya</option>
                                </select>
                            </div>

                            <!-- Reference Number -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    No. Referensi (Optional)
                                </label>
                                <input
                                    type="text"
                                    wire:model="reference_number"
                                    placeholder="No. bukti transfer, cek, dll."
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                                />
                            </div>

                            <!-- Recommended Amount Info -->
                            @if($selected_debt)
                            <div class="md:col-span-2">
                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                                    <div class="text-sm">
                                        <div class="font-medium text-blue-800 dark:text-blue-300 mb-1">Informasi Pembayaran:</div>
                                        @if($selected_debt->cicilan_per_bulan > 0)
                                            <div class="text-xs text-blue-700 dark:text-blue-400">
                                                Cicilan bulanan yang direkomendasikan: <strong>Rp {{ number_format($selected_debt->cicilan_per_bulan, 2, ',', '.') }}</strong>
                                            </div>
                                        @endif
                                        <div class="text-xs text-blue-700 dark:text-blue-400 mt-1">
                                            Maksimal yang dapat dibayar: <strong>Rp {{ number_format($selected_debt->sisa_hutang, 2, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="amount">
                        Jumlah (Rp)
                    </label>
                    <input 
                        id="amount"
                        class="w-full px-3 py-2 border {{ $errors->has('amount') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="number" 
                        step="0.01"
                        wire:model="amount"
                        placeholder="Masukkan jumlah"
                    />
                    @error('amount') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Sumber/Tujuan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="source_destination">
                        Sumber/Tujuan
                    </label>
                    <input 
                        id="source_destination"
                        class="w-full px-3 py-2 border {{ $errors->has('source_destination') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        wire:model="source_destination"
                        placeholder="Masukkan sumber atau tujuan"
                    />
                    @error('source_destination') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Diterima oleh -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="received_by">
                        Diterima oleh
                    </label>
                    <input 
                        id="received_by"
                        class="w-full px-3 py-2 border {{ $errors->has('received_by') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        wire:model="received_by"
                        placeholder="Masukkan nama orang yang menerima"
                    />
                    @error('received_by') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="category">
                        Kategori
                    </label>
                    <input 
                        id="category"
                        class="w-full px-3 py-2 border {{ $errors->has('category') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        wire:model="category"
                        placeholder="Masukkan kategori transaksi"
                    />
                    @error('category') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- KP Reference -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="kp_id">
                        KP Reference (Optional)
                    </label>
                    <select 
                        id="kp_id"
                        class="w-full px-3 py-2 border {{ $errors->has('kp_id') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        wire:model="kp_id"
                    >
                        <option value="">Select KP Transaction</option>
                        @foreach(\App\Models\KeuanganPerusahaan::orderBy('transaction_date', 'desc')->get() as $kp)
                            <option value="{{ $kp->id }}">{{ $kp->transaction_number }} - {{ $kp->source_destination }}</option>
                        @endforeach
                    </select>
                    @error('kp_id') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="notes">
                        Catatan
                    </label>
                    <textarea 
                        id="notes"
                        class="w-full px-3 py-2 border {{ $errors->has('notes') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        wire:model="notes"
                        placeholder="Masukkan catatan tambahan"
                        rows="2"
                    ></textarea>
                    @error('notes') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Dokumen Bukti -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="proof_document">
                        Dokumen Bukti
                    </label>
                    <input
                        id="proof_document"
                        type="file"
                        wire:model.lazy="proof_document"
                        class="w-full px-3 py-2 border {{ $errors->has('proof_document') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                    />
                    @if($isEditing && $proof_document === null)
                        <div class="mt-2">
                            <small class="text-gray-500">Leave blank to keep existing document</small>
                        </div>
                    @endif
                    @error('proof_document') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeCreateModal" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="saveTransactionModal" wire:loading.attr="disabled">
                {{ $isEditing ? 'Update' : 'Save' }} Transaction
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Delete Confirmation Modal -->
    <x-confirmation-modal wire:model.live="showDeleteConfirmation">
        <x-slot name="title">
            {{ __('Hapus Transaksi Buku Kas') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Apakah Anda yakin ingin menghapus transaksi buku kas ":name"?', ['name' => $deletingTransactionName]) }}
            {{ __('Setelah catatan dihapus, semua datanya akan dihapus secara permanen.') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeDeleteConfirmation" wire:loading.attr="disabled">
                {{ __('Batal') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteTransactionConfirmed" wire:loading.attr="disabled">
                {{ __('Hapus Transaksi') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- Photo Preview Modal -->
    <x-dialog-modal wire:model.live="showPhotoModal" maxWidth="2xl">
        <x-slot name="title">
            Pratinjau Dokumen
        </x-slot>

        <x-slot name="content">
            @if($photoToView)
                <div class="flex justify-center">
                    <img src="{{ asset('storage/' . $photoToView) }}" alt="Document" class="max-w-full h-auto rounded-lg shadow-md">
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closePhotoModal" wire:loading.attr="disabled">
                {{ __('Close') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Related KP Transaction Modal -->
    <x-dialog-modal wire:model.live="showRelatedKp" maxWidth="4xl">
        <x-slot name="title">
            Related KP Transaction for BKK #{{ $selectedBkkId ? \App\Models\BukuKasKebun::find($selectedBkkId)?->transaction_number : '' }}
        </x-slot>

        <x-slot name="content">
            @if($relatedKpTransaction)
                <div class="mb-4">
                    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg dark:bg-blue-500/10 dark:border-blue-500/30 dark:text-blue-500">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-medium">Source KP Transaction</span>
                        </div>
                        <p class="text-sm mt-1">This BKK transaction was automatically generated from the KP transaction below.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- KP Transaction Details -->
                    <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-3">KP Transaction Details</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Transaction #:</span>
                                <span class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $relatedKpTransaction->transaction_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Date:</span>
                                <span class="text-sm text-gray-800 dark:text-gray-100">{{ $relatedKpTransaction->transaction_date->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Type:</span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $relatedKpTransaction->transaction_type === 'income' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-800/30 dark:text-emerald-500' : 
                                               'bg-rose-100 text-rose-800 dark:bg-rose-800/30 dark:text-rose-500' }}">
                                    {{ ucfirst($relatedKpTransaction->transaction_type) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Amount:</span>
                                <span class="text-sm font-medium {{ $relatedKpTransaction->transaction_type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                    {{ $relatedKpTransaction->transaction_type === 'income' ? '+' : '-' }}Rp {{ number_format($relatedKpTransaction->amount, 2, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Kategori:</span>
                                <span class="text-sm text-gray-800 dark:text-gray-100">{{ $relatedKpTransaction->category }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Sumber/Tujuan:</span>
                                <span class="text-sm text-gray-800 dark:text-gray-100">{{ $relatedKpTransaction->source_destination }}</span>
                            </div>
                            @if($relatedKpTransaction->received_by)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Diterima oleh:</span>
                                    <span class="text-sm text-gray-800 dark:text-gray-100">{{ $relatedKpTransaction->received_by }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- BKK Transaction Details -->
                    <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-3">BKK Transaction Details</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Transaction #:</span>
                                <span class="text-sm font-medium text-gray-800 dark:text-gray-100">
                                    {{ \App\Models\BukuKasKebun::find($selectedBkkId)?->transaction_number }}
                                    @if(str_contains(\App\Models\BukuKasKebun::find($selectedBkkId)?->transaction_number ?? '', 'BKK-AUTO'))
                                        <span class="ml-2 px-2 py-1 text-xs bg-emerald-100 text-emerald-800 dark:bg-emerald-800/30 dark:text-emerald-500 rounded-full">
                                                    Auto
                                                </span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Date:</span>
                                <span class="text-sm text-gray-800 dark:text-gray-100">{{ \App\Models\BukuKasKebun::find($selectedBkkId)?->transaction_date->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Type:</span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ \App\Models\BukuKasKebun::find($selectedBkkId)?->transaction_type === 'income' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-800/30 dark:text-emerald-500' : 
                                               'bg-rose-100 text-rose-800 dark:bg-rose-800/30 dark:text-rose-500' }}">
                                    {{ ucfirst(\App\Models\BukuKasKebun::find($selectedBkkId)?->transaction_type) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Amount:</span>
                                <span class="text-sm font-medium text-emerald-600 dark:text-emerald-400">
                                    +Rp {{ number_format(\App\Models\BukuKasKebun::find($selectedBkkId)?->amount, 2, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Kategori:</span>
                                <span class="text-sm text-gray-800 dark:text-gray-100">{{ \App\Models\BukuKasKebun::find($selectedBkkId)?->category }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Sumber/Tujuan:</span>
                                <span class="text-sm text-gray-800 dark:text-gray-100">{{ \App\Models\BukuKasKebun::find($selectedBkkId)?->source_destination }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
                                <span class="px-2 py-1 text-xs bg-emerald-100 text-emerald-800 dark:bg-emerald-800/30 dark:text-emerald-500 rounded-full">
                                    Auto-generated
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                <div class="mt-6">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">Notes & Audit Trail</h3>
                    <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg">
                        @if($relatedKpTransaction->notes)
                            <div class="mb-3">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">KP Notes:</span>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $relatedKpTransaction->notes }}</p>
                            </div>
                        @endif
                        @if(\App\Models\BukuKasKebun::find($selectedBkkId)?->notes)
                            <div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">BKK Notes:</span>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ \App\Models\BukuKasKebun::find($selectedBkkId)?->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end space-x-3">
                    <a 
                        href="{{ route('keuangan-perusahaan') }}#transaction-{{ $relatedKpTransaction->id }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                        target="_blank"
                    >
                        View KP Transaction
                    </a>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">No related KP transaction found for this BKK entry.</p>
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="hideRelatedKpTransaction" wire:loading.attr="disabled">
                {{ __('Close') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Import Modal -->
    <x-dialog-modal wire:model.live="showImportModal" maxWidth="2xl">
        <x-slot name="title">
            Import Buku Kas Kebun Data from Excel
        </x-slot>

        <x-slot name="content">
            <div class="mb-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Upload an Excel file to import Buku Kas Kebun data. The file should contain the following columns:
                </p>
                <ul class="mt-2 text-sm text-gray-600 dark:text-gray-400 list-disc list-inside">
                    <li><strong>transaction_date</strong> - Date of transaction (format: DD-MM-YYYY)</li>
                    <li><strong>transaction_type</strong> - Type of transaction (income or expense)</li>
                    <li><strong>amount</strong> - Amount value</li>
                    <li><strong>source_destination</strong> - Source or destination</li>
                    <li><strong>received_by</strong> - Person who received (optional)</li>
                    <li><strong>notes</strong> - Additional notes (optional)</li>
                    <li><strong>category</strong> - Kategori of the transaction</li>
                    <li><strong>kp_id</strong> - Keuangan Perusahaan reference ID (optional)</li>
                </ul>
            </div>
            
            <form wire:submit.prevent="importTransaction" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="importFile">
                        Excel File
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
                    Supported formats: .xlsx, .xls, .csv
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeImportModal" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="importTransaction" wire:loading.attr="disabled">
                Import Data
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Close debt suggestions when clicking outside
    document.addEventListener('click', function(event) {
        const debtInput = event.target.closest('[wire\\:model\\.live="debt_search"]');
        const suggestionsContainer = event.target.closest('.absolute.z-50');

        if (!debtInput && !suggestionsContainer) {
            @this.closeDebtSuggestions();
        }
    });
});
</script>