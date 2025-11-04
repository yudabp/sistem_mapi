<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Page header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Data Produksi</h1>
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
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm p-5">
            <div class="flex justify-between items-start">
                <div class="flex items-center">
                    <div class="bg-violet-100 dark:bg-violet-500/30 p-3 rounded-lg mr-4">
                        <svg class="w-6 h-6 fill-current text-violet-500 dark:text-violet-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M5 10h2v10H5V10zm4-2h2v12H9V8zm4 6h2v6h-2v-6z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 mb-1 flex justify-between items-center">
                            <span>Total TBS</span>
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
                            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ number_format($total_tbs, 2) }} TBS</div>
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
                    <div class="bg-emerald-100 dark:bg-emerald-500/30 p-3 rounded-lg mr-4">
                        <svg class="w-6 h-6 fill-current text-emerald-500 dark:text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M5 10h2v10H5V10zm4-2h2v12H9V8zm4 6h2v6h-2v-6z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 mb-1 flex justify-between items-center">
                            <span>Total KG</span>
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
                            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ number_format($total_kg, 2) }} KG</div>
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

    <!-- Data Produksi Modal -->
    <x-dialog-modal wire:model.live="showModal" maxWidth="2xl">
        <x-slot name="title">
            {{ $isEditing ? 'Edit Data Produksi' : 'Tambah Data Produksi' }}
        </x-slot>

        <x-slot name="content">


            <form wire:submit.prevent="saveProductionModal" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Transaction Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="transaction_number">
                        Nomor Transaksi
                    </label>
                    <input
                        id="transaction_number"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-600"
                        type="text"
                        wire:model="transaction_number"
                        placeholder="Auto-generated"
                        @if(!$isEditing) readonly @endif
                    />
                    @if(!$isEditing)
                        <small class="text-gray-500 dark:text-gray-400">Nomor transaksi akan di-generate otomatis</small>
                    @endif
                    @error('transaction_number')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="date">
                        Tanggal (DD-MM-YYYY)
                    </label>
                    <input 
                        id="date"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        placeholder="DD-MM-YYYY"
                        wire:model="date"
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
                    @error('date') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- SP Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="sp_number">
                        Nomor SP
                    </label>
                    <input 
                        id="sp_number"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        wire:model="sp_number"
                        placeholder="Masukkan nomor SP"
                    />
                    @error('sp_number') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Vehicle Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="vehicle_id">
                        Nomor Kendaraan (No Polisi) <span class="text-gray-400 text-xs">(Opsional)</span>
                    </label>
                    <select
                        id="vehicle_id"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                        wire:model.lazy="vehicle_id"
                    >
                        <option value="">Pilih No Polisi</option>
                        @foreach($vehicle_numbers as $vehicle_number_option)
                            <option value="{{ $vehicle_number_option->id }}">{{ $vehicle_number_option->number }}</option>
                        @endforeach
                    </select>
                    <small class="text-gray-500 dark:text-gray-400 text-xs">Dikosongkan jika tidak ada kendaraan</small>
                    @error('vehicle_id')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- TBS Quantity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="tbs_quantity">
                        TBS Quantity (KG) <span class="text-gray-400 text-xs">(Opsional)</span>
                    </label>
                    <input
                        id="tbs_quantity"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                        type="number"
                        step="0.01"
                        wire:model="tbs_quantity"
                        placeholder="Kosongkan jika tidak ada TBS"
                    />
                    <small class="text-gray-500 dark:text-gray-400 text-xs">Dikosongkan jika tidak ada data TBS</small>
                    @error('tbs_quantity')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- KG Quantity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="kg_quantity">
                        KG Quantity
                    </label>
                    <input 
                        id="kg_quantity"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="number" 
                        step="0.01"
                        wire:model="kg_quantity"
                        placeholder="Enter KG quantity"
                    />
                    @error('kg_quantity') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Division -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="division_id">
                        Divisi (Afdeling)
                    </label>
                    <select 
                        id="division_id"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        wire:model="division_id"
                    >
                        <option value="">Pilih Afdeling</option>
                        @foreach($divisions as $division_option)
                            <option value="{{ $division_option->id }}">{{ $division_option->name }}</option>
                        @endforeach
                    </select>
                    @error('division_id') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- PKS -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="pks_id">
                        Stasiun Pengolahan (PKS)
                    </label>
                    <select 
                        id="pks_id"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        wire:model="pks_id"
                    >
                        <option value="">Pilih PKS</option>
                        @foreach($pks_list as $pks_option)
                            <option value="{{ $pks_option->id }}">{{ $pks_option->name }}</option>
                        @endforeach
                    </select>
                    @error('pks_id') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- SP Photo -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="sp_photo">
                        Foto SP
                    </label>
                    <input 
                        id="sp_photo"
                        type="file" 
                        wire:model="sp_photo"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                    />
                    @if($isEditing && $sp_photo === null)
                        <div class="mt-2">
                            <small class="text-gray-500">Leave blank to keep existing photo</small>
                        </div>
                    @endif
                    @error('sp_photo') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeCreateModal" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="saveProductionModal" wire:loading.attr="disabled">
                {{ $isEditing ? 'Perbarui' : 'Simpan' }} Data Produksi
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Delete Confirmation Modal -->
    <x-confirmation-modal wire:model.live="showDeleteConfirmation">
        <x-slot name="title">
            {{ __('Hapus Data Produksi') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Apakah Anda yakin ingin menghapus data produksi ":name"?', ['name' => $deletingProductionName]) }}
            {{ __('Setelah data dihapus, semua informasi akan dihapus secara permanen.') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeDeleteConfirmation" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteProductionConfirmed" wire:loading.attr="disabled">
                {{ __('Hapus Produksi') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- Photo Preview Modal -->
    <x-dialog-modal wire:model.live="showPhotoModal" maxWidth="2xl">
        <x-slot name="title">
            Preview Foto SP
        </x-slot>

        <x-slot name="content">
            @if($photoToView)
                <div class="flex justify-center">
                    <img src="{{ asset('storage/' . $photoToView) }}" alt="SP Photo" class="max-w-full h-auto rounded-lg shadow-md">
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showPhotoModal', false)" wire:loading.attr="disabled">
                {{ __('Close') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Import Modal -->
    <x-dialog-modal wire:model.live="showImportModal" maxWidth="2xl">
        <x-slot name="title">
            Import Data Produksi dari Excel
        </x-slot>

        <x-slot name="content">
            <div class="mb-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Unggah file Excel untuk mengimpor data produksi. File harus mengandung kolom-kolom berikut:
                </p>
                <ul class="mt-2 text-sm text-gray-600 dark:text-gray-400 list-disc list-inside">
                    <li><strong>transaction_number</strong> - Pengenal unik untuk transaksi</li>
                    <li><strong>date</strong> - Tanggal produksi (format: YYYY-MM-DD)</li>
                    <li><strong>sp_number</strong> - Nomor SP</li>
                    <li><strong>vehicle_number</strong> - Nomor Kendaraan</li>
                    <li><strong>tbs_quantity</strong> - Jumlah TBS dalam KG</li>
                    <li><strong>kg_quantity</strong> - Jumlah KG</li>
                    <li><strong>division</strong> - Divisi/Afdeling</li>
                    <li><strong>pks</strong> - Stasiun Pengolahan</li>
                </ul>
            </div>
            
            <form wire:submit.prevent="importProduction" class="space-y-4">
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

            <x-button class="ms-3" wire:click="importProduction" wire:loading.attr="disabled">
                Impor Data
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Form Section - Buttons to open modal and import/export -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-8">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">Data Produksi</h2>
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
                                href="{{ route('production.sample.download') }}"
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
            </div>
        </header>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-8 p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari</label>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Cari berdasarkan No. Transaksi, No. SP, No. Kendaraan, atau Divisi"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
            />
            @if($search)
                <p class="text-xs text-gray-500 mt-1">Mencari: "{{ $search }}"</p>
            @endif
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
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter berdasarkan Divisi</label>
            <select
                wire:model.live="divisionFilter"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
            >
                <option value="">Semua Divisi</option>
                @foreach($divisions as $division_option)
                    <option value="{{ $division_option->name }}">{{ $division_option->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">Data Produksi</h2>
                <div class="text-sm text-gray-500">
                    Menampilkan {{ $productions->count() }} data
                    @if($search || $dateFilter || $divisionFilter)
                        <span class="text-violet-600">- Difilter</span>
                    @endif
                </div>
            </div>
        </header>
        <div class="p-3">
            <div class="overflow-x-auto">
                <!-- Loading indicator -->
                <div wire:loading class="p-4 text-center text-gray-500">
                    Memuat data produksi...
                </div>
                <!-- Table -->
                <div wire:loading.remove>
                    <table class="table-auto w-full">
                        <thead>
                            <tr class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/30">
                                <th class="p-2 whitespace-nowrap">No. Transaksi</th>
                                <th class="p-2 whitespace-nowrap">Tanggal</th>
                                <th class="p-2 whitespace-nowrap">No. SP</th>
                                <th class="p-2 whitespace-nowrap">No. Kendaraan</th>
                                <th class="p-2 whitespace-nowrap">TBS (KG)</th>
                                <th class="p-2 whitespace-nowrap">KG</th>
                                <th class="p-2 whitespace-nowrap">Divisi</th>
                                <th class="p-2 whitespace-nowrap">PKS</th>
                                <th class="p-2 whitespace-nowrap">Foto SP</th>
                                @canedit
                                <th class="p-2 whitespace-nowrap">Aksi</th>
                                @else
                                <!-- <th class="p-2 whitespace-nowrap">Actions (Direksi cannot edit)</th> -->
                                @endcanedit
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse($productions as $production)
                                <tr>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="text-left font-medium text-gray-800 dark:text-gray-100">{{ $production->transaction_number }}</div>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="text-left">{{ $production->date->format('d M Y') }}</div>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="text-left">{{ $production->sp_number }}</div>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="text-left">{{ $production->vehicle ? $production->vehicle->number : ($production->vehicle_number ?? '-') }}</div>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="text-left">{{ $production->tbs_quantity ? number_format($production->tbs_quantity, 2) : '-' }}</div>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="text-left">{{ number_format($production->kg_quantity, 2) }}</div>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="text-left">{{ $production->divisionRel ? $production->divisionRel->name : $production->division }}</div>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="text-left">{{ $production->pksRel ? $production->pksRel->name : $production->pks }}</div>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        @if($production->sp_photo_path)
                                            <button
                                                wire:click="showPhoto('{{ $production->sp_photo_path }}')"
                                                class="text-blue-600 hover:underline dark:text-blue-400"
                                            >
                                                Lihat Foto
                                            </button>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">Tidak ada foto</span>
                                        @endif
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="flex space-x-2">
                                            @canedit
                                                <button
                                                    wire:click="openEditModal({{ $production->id }})"
                                                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm"
                                                >
                                                    Edit
                                                </button>
                                                <button
                                                    wire:click="confirmDelete({{ $production->id }}, '{{ $production->transaction_number }}')"
                                                    class="px-3 py-1 bg-rose-600 text-white rounded hover:bg-rose-700 text-sm"
                                                >
                                                    Hapus
                                                </button>
                                            @else
                                                <!-- <button
                                                    disabled
                                                    class="px-3 py-1 bg-gray-400 text-white rounded cursor-not-allowed text-sm"
                                                    title="Direksi tidak dapat mengedit data"
                                                >
                                                    Edit
                                                </button>
                                                <button
                                                    disabled
                                                    class="px-3 py-1 bg-gray-400 text-white rounded cursor-not-allowed text-sm"
                                                    title="Direksi tidak dapat menghapus data"
                                                >
                                                    Delete
                                                </button> -->
                                            @endcanedit
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="p-2 text-center text-gray-500 dark:text-gray-400">
                                        Tidak ada data produksi
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>