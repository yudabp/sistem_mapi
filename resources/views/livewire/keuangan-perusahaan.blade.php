<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Page header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Keuangan Perusahaan (KP)</h1>
    </div>

    <!-- Persistent Message -->
    @if($persistentMessage)
        <div class="mb-6">
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-lg dark:bg-emerald-500/10 dark:text-emerald-500 flex justify-between items-center">
                <span>{{ $persistentMessage }}</span>
                <button wire:click="clearPersistentMessage" class="text-emerald-700 dark:text-emerald-500 hover:text-emerald-900 dark:hover:text-emerald-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
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
                            <span>Total Income</span>
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
                            <span>Total Expenses</span>
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
                            <span>Balance</span>
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
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">Keuangan Perusahaan Transaction Input</h2>
                <div class="flex flex-wrap gap-2">
                    <!-- Import button with dropdown -->
                    <div class="relative group">
                        <button 
                            type="button"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                            Import
                        </button>
                        <div class="absolute right-0 mt-0 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-10 hidden group-hover:block">
                            <button 
                                wire:click="openImportModal"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Upload Excel
                            </button>
                            <button 
                                wire:click="downloadSampleExcel"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download Sample
                            </button>
                        </div>
                    </div>
                    
                    <!-- Export button with dropdown -->
                    <div class="relative group">
                        <button 
                            type="button"
                            class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Export
                        </button>
                        <div class="absolute right-0 mt-0 w-56 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-10 hidden group-hover:block">
                            <div class="px-4 py-2 text-xs font-medium text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                                Export Options
                            </div>
                            <div class="px-4 py-2">
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
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
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">End Date</label>
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
                                Export to Excel
                            </button>
                            <button 
                                wire:click="exportToPdf"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export to PDF
                            </button>
                        </div>
                    </div>
                    
                    <button 
                        wire:click="openCreateModal"
                        class="px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors flex items-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Transaction
                    </button>
                </div>
            </div>
        </header>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-8 p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
            <input 
                type="text" 
                wire:model.live="search"
                placeholder="Search by source, destination, or category"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
            />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter by Date</label>
            <input 
                type="month" 
                wire:model.live="dateFilter"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
            />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter by Type</label>
            <select 
                wire:model.live="typeFilter"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
            >
                <option value="">All Types</option>
                <option value="income">Income</option>
                <option value="expense">Expense</option>
            </select>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Keuangan Perusahaan Transactions</h2>
        </header>
        <div class="p-3">
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/30">
                            <th class="p-2 whitespace-nowrap">Date</th>
                            <th class="p-2 whitespace-nowrap">Transaction #</th>
                            <th class="p-2 whitespace-nowrap">Type</th>
                            <th class="p-2 whitespace-nowrap">Source/Destination</th>
                            <th class="p-2 whitespace-nowrap">Amount</th>
                            <th class="p-2 whitespace-nowrap">Category</th>
                            <th class="p-2 whitespace-nowrap">Proof</th>
                            <th class="p-2 whitespace-nowrap">Actions</th>
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
                                        @if($transaction->transaction_type === 'expense' && $transaction->bukuKasKebun && $transaction->bukuKasKebun->count() > 0)
                                            <span class="ml-2 px-2 py-1 text-xs bg-emerald-100 text-emerald-800 dark:bg-emerald-800/30 dark:text-emerald-500 rounded-full">
                                                BKK Created
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
                                    @if($transaction->proof_document_path)
                                        <button 
                                            wire:click="showPhoto('{{ $transaction->proof_document_path }}')"
                                            class="text-blue-600 hover:underline dark:text-blue-400"
                                        >
                                            View Document
                                        </button>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">No proof</span>
                                    @endif
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        @if($transaction->transaction_type === 'expense' && $transaction->bukuKasKebun->count() > 0)
                                            <button 
                                                wire:click="showRelatedBkkTransactions({{ $transaction->id }})"
                                                class="px-3 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-700 text-sm"
                                                title="View related BKK entries"
                                            >
                                                BKK ({{ $transaction->bukuKasKebun->count() }})
                                            </button>
                                        @endif
                                        <button 
                                            wire:click="openEditModal({{ $transaction->id }})"
                                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm"
                                        >
                                            Edit
                                        </button>
                                        <button 
                                            wire:click="confirmDelete({{ $transaction->id }}, '{{ $transaction->transaction_number }}')"
                                            class="px-3 py-1 bg-rose-600 text-white rounded hover:bg-rose-700 text-sm"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-2 text-center text-gray-500 dark:text-gray-400">
                                    No Keuangan Perusahaan transactions found
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
            {{ $isEditing ? 'Edit Keuangan Perusahaan Transaction' : 'Add Keuangan Perusahaan Transaction' }}
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
                        Transaction Date (DD-MM-YYYY)
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

                <!-- Transaction Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="transaction_type">
                        Transaction Type
                    </label>
                    <select 
                        id="transaction_type"
                        class="w-full px-3 py-2 border {{ $errors->has('transaction_type') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        wire:model="transaction_type"
                    >
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                    @error('transaction_type') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="amount">
                        Amount (Rp)
                    </label>
                    <input 
                        id="amount"
                        class="w-full px-3 py-2 border {{ $errors->has('amount') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="number" 
                        step="0.01"
                        wire:model="amount"
                        placeholder="Enter amount"
                    />
                    @error('amount') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Source/Destination -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="source_destination">
                        Source/Destination
                    </label>
                    <input 
                        id="source_destination"
                        class="w-full px-3 py-2 border {{ $errors->has('source_destination') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        wire:model="source_destination"
                        placeholder="Enter source or destination"
                    />
                    @error('source_destination') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Received By -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="received_by">
                        Received By
                    </label>
                    <input 
                        id="received_by"
                        class="w-full px-3 py-2 border {{ $errors->has('received_by') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        wire:model="received_by"
                        placeholder="Enter name of person who received"
                    />
                    @error('received_by') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="category">
                        Category
                    </label>
                    <input 
                        id="category"
                        class="w-full px-3 py-2 border {{ $errors->has('category') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        wire:model="category"
                        placeholder="Enter transaction category"
                    />
                    @error('category') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="notes">
                        Notes
                    </label>
                    <textarea 
                        id="notes"
                        class="w-full px-3 py-2 border {{ $errors->has('notes') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        wire:model="notes"
                        placeholder="Enter additional notes"
                        rows="2"
                    ></textarea>
                    @error('notes') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Proof Document -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="proof_document">
                        Proof Document
                    </label>
                    <input 
                        id="proof_document"
                        type="file" 
                        wire:model="proof_document"
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
            {{ __('Delete Keuangan Perusahaan Transaction') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete the Keuangan Perusahaan transaction ":name"?', ['name' => $deletingTransactionName]) }}
            {{ __('Once the record is deleted, all of its data will be permanently removed.') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeDeleteConfirmation" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteTransactionConfirmed" wire:loading.attr="disabled">
                {{ __('Delete Transaction') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- Photo Preview Modal -->
    <x-dialog-modal wire:model.live="showPhotoModal" maxWidth="2xl">
        <x-slot name="title">
            Document Preview
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

    <!-- KP Expense Creation Confirmation Modal -->
    <x-confirmation-modal wire:model.live="showExpenseConfirmation">
        <x-slot name="title">
            {{ __('Confirm KP Expense Creation') }}
        </x-slot>

        <x-slot name="content">
            <div class="bg-amber-50 border border-amber-200 text-amber-700 p-4 rounded-lg dark:bg-amber-500/10 dark:border-amber-500/30 dark:text-amber-500 mb-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">Auto-BKK Creation Notice</span>
                </div>
                <p class="text-sm mt-1">Creating this KP expense will automatically generate a corresponding BKK income entry.</p>
            </div>
            
            <div class="space-y-2">
                <p><strong>Transaction Details:</strong></p>
                <ul class="list-disc list-inside text-sm space-y-1">
                    <li><strong>Type:</strong> Expense (Rp {{ number_format((float)$amount, 2, ',', '.') }})</li>
                    <li><strong>Source/Destination:</strong> {{ $source_destination }}</li>
                    <li><strong>Category:</strong> {{ $category }}</li>
                    <li><strong>Date:</strong> {{ $transaction_date ? \Carbon\Carbon::parse($transaction_date)->format('d M Y') : '' }}</li>
                </ul>
            </div>
            
            <div class="mt-4 p-3 bg-gray-50 border border-gray-200 rounded-lg dark:bg-gray-700/30 dark:border-gray-600">
                <p class="text-sm"><strong>Auto-generated BKK Entry:</strong></p>
                <ul class="list-disc list-inside text-sm space-y-1 mt-1">
                    <li><strong>Type:</strong> Income (Rp {{ number_format((float)$amount, 2, ',', '.') }})</li>
                    <li><strong>Source:</strong> Keuangan Perusahaan (Auto-generated)</li>
                    <li><strong>Category:</strong> Operational Cost</li>
                    <li><strong>Transaction #:</strong> BKK-AUTO-XXXXXX</li>
                </ul>
            </div>
            
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-4">
                {{ __('Are you sure you want to proceed with creating this KP expense and the auto-generated BKK entry?') }}
            </p>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeExpenseConfirmation" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="confirmExpenseCreation" wire:loading.attr="disabled">
                {{ __('Create Expense & Auto-BKK') }}
            </x-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- Related BKK Transactions Modal -->
    <x-dialog-modal wire:model.live="showRelatedBkk" maxWidth="4xl">
        <x-slot name="title">
            Related BKK Transactions for KP #{{ $selectedKpId ? \App\Models\KeuanganPerusahaan::find($selectedKpId)?->transaction_number : '' }}
        </x-slot>

        <x-slot name="content">
            @if($relatedBkkTransactions->count() > 0)
                <div class="mb-4">
                    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg dark:bg-blue-500/10 dark:border-blue-500/30 dark:text-blue-500">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-medium">Auto-generated BKK entries found</span>
                        </div>
                        <p class="text-sm mt-1">These BKK transactions were automatically created when KP expenses were recorded.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="table-auto w-full">
                        <thead>
                            <tr class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/30">
                                <th class="p-2 whitespace-nowrap">BKK Transaction #</th>
                                <th class="p-2 whitespace-nowrap">Date</th>
                                <th class="p-2 whitespace-nowrap">Type</th>
                                <th class="p-2 whitespace-nowrap">Amount</th>
                                <th class="p-2 whitespace-nowrap">Category</th>
                                <th class="p-2 whitespace-nowrap">Source/Destination</th>
                                <th class="p-2 whitespace-nowrap">Status</th>
                                <th class="p-2 whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                            @foreach($relatedBkkTransactions as $bkkTransaction)
                                <tr>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="text-left font-medium text-gray-800 dark:text-gray-100">
                                            {{ $bkkTransaction->transaction_number }}
                                            @if(str_contains($bkkTransaction->transaction_number, 'BKK-AUTO'))
                                                <span class="ml-2 px-2 py-1 text-xs bg-emerald-100 text-emerald-800 dark:bg-emerald-800/30 dark:text-emerald-500 rounded-full">
                                                    Auto
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="text-left">{{ $bkkTransaction->transaction_date->format('d M Y') }}</div>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $bkkTransaction->transaction_type === 'income' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-800/30 dark:text-emerald-500' : 
                                               'bg-rose-100 text-rose-800 dark:bg-rose-800/30 dark:text-rose-500' }}">
                                            {{ ucfirst($bkkTransaction->transaction_type) }}
                                        </span>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="text-left font-medium text-emerald-600 dark:text-emerald-400">
                                            +Rp {{ number_format($bkkTransaction->amount, 2, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="text-left">{{ $bkkTransaction->category }}</div>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="text-left">{{ $bkkTransaction->source_destination }}</div>
                                        @if($bkkTransaction->received_by)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Received by: {{ $bkkTransaction->received_by }}</div>
                                        @endif
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        @if(str_contains($bkkTransaction->transaction_number, 'BKK-AUTO'))
                                            <span class="px-2 py-1 text-xs bg-emerald-100 text-emerald-800 dark:bg-emerald-800/30 dark:text-emerald-500 rounded-full">
                                                Auto-generated
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 dark:bg-gray-700/30 dark:text-gray-500 rounded-full">
                                                Manual
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="flex space-x-2">
                                            <a 
                                                href="{{ route('buku-kas-kebun') }}#transaction-{{ $bkkTransaction->id }}"
                                                class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm"
                                                target="_blank"
                                            >
                                                View in BKK
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">No related BKK transactions found for this KP entry.</p>
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="hideRelatedBkkTransactions" wire:loading.attr="disabled">
                {{ __('Close') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Import Modal -->
    <x-dialog-modal wire:model.live="showImportModal" maxWidth="2xl">
        <x-slot name="title">
            Import Keuangan Perusahaan Data from Excel
        </x-slot>

        <x-slot name="content">
            <div class="mb-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Upload an Excel file to import Keuangan Perusahaan data. The file should contain the following columns:
                </p>
                <ul class="mt-2 text-sm text-gray-600 dark:text-gray-400 list-disc list-inside">
                    <li><strong>transaction_date</strong> - Date of transaction (format: DD-MM-YYYY)</li>
                    <li><strong>transaction_type</strong> - Type of transaction (income or expense)</li>
                    <li><strong>amount</strong> - Amount value</li>
                    <li><strong>source_destination</strong> - Source or destination</li>
                    <li><strong>received_by</strong> - Person who received (optional)</li>
                    <li><strong>notes</strong> - Additional notes (optional)</li>
                    <li><strong>category</strong> - Category of the transaction</li>
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