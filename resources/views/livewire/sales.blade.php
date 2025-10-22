<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Page header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Data Penjualan</h1>
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
                            <span>Total KG Sold</span>
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
                        type="date" 
                        wire:model.live="startDate"
                        class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded shadow-sm focus:outline-none focus:ring-1 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                    />
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Tanggal akhir</label>
                    <input 
                        type="date" 
                        wire:model.live="endDate"
                        class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded shadow-sm focus:outline-none focus:ring-1 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
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
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 mb-1 flex justify-between items-center">
                            <span>Total Sales</span>
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
                            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">Rp {{ number_format($total_sales, 2, ',', '.') }}</div>
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
                        type="date" 
                        wire:model.live="startDate"
                        class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded shadow-sm focus:outline-none focus:ring-1 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                    />
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Tanggal akhir</label>
                    <input 
                        type="date" 
                        wire:model.live="endDate"
                        class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded shadow-sm focus:outline-none focus:ring-1 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                    />
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Sales Data Modal -->
    <x-dialog-modal wire:model.live="showModal" maxWidth="2xl">
        <x-slot name="title">
            {{ $isEditing ? 'Edit Sales Record' : 'Add Sales Record' }}
        </x-slot>

        <x-slot name="content">


            <form wire:submit.prevent="saveSalesModal" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- SP Number with Autocomplete -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="sp_search">
                        SP Number
                    </label>
                    <div class="relative">
                        <input 
                            id="sp_search"
                            type="text" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                            wire:model.live="sp_search"
                            placeholder="Ketik SP Number..."
                            autocomplete="off"
                        />
                        @if($sp_search)
                            <button 
                                type="button"
                                wire:click="clearSpSelection"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                    
                    <!-- Autocomplete Suggestions -->
                    @if($showSpSuggestions && count($spSuggestions) > 0)
                        <div class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            @foreach($spSuggestions as $suggestion)
                                <button 
                                    type="button"
                                    wire:click="selectSpSuggestion({{ json_encode($suggestion) }})"
                                    class="w-full px-3 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-600 last:border-b-0 transition-colors duration-150"
                                >
                                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ $suggestion['sp_number'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        TBS: {{ number_format($suggestion['tbs_quantity'], 2) }} KG | 
                                        KG: {{ number_format($suggestion['kg_quantity'], 2) }}
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @endif
                    
                    @error('sp_number') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Script for autocomplete -->
                <script>
                    document.addEventListener('click', function(event) {
                        const spContainer = event.target.closest('.relative');
                        if (!spContainer || !spContainer.querySelector('[wire\\:model\\.live="sp_search"]')) {
                            @this.set('showSpSuggestions', false);
                        }
                    });
                </script>

                <!-- TBS Quantity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="tbs_quantity">
                        TBS Quantity (KG)
                        @if($production_id)
                            <span class="text-xs text-gray-500 ml-1">(Auto-filled dari produksi)</span>
                        @endif
                    </label>
                    <input 
                        id="tbs_quantity"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="number" 
                        step="0.01"
                        wire:model="tbs_quantity"
                        placeholder="Enter TBS quantity"
                    />
                    @error('tbs_quantity') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- KG Quantity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="kg_quantity">
                        KG Quantity
                        @if($production_id)
                            <span class="text-xs text-gray-500 ml-1">(Auto-filled dari produksi)</span>
                        @endif
                    </label>
                    <input 
                        id="kg_quantity"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="number" 
                        step="0.01"
                        wire:model.live="kg_quantity"
                        placeholder="Enter KG quantity"
                    />
                    @error('kg_quantity') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Price per KG -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="price_per_kg">
                        Price per KG (Rp)
                    </label>
                    <input 
                        id="price_per_kg"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="number" 
                        step="0.01"
                        wire:model.live="price_per_kg"
                        placeholder="Enter price per KG"
                    />
                    @error('price_per_kg') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Total Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="total_amount">
                        Total Amount (Rp)
                    </label>
                    <input 
                        id="total_amount"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600" 
                        type="number" 
                        step="0.01"
                        wire:model="total_amount"
                        readonly
                    />
                </div>

                <!-- Sale Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="sale_date">
                        Sale Date
                    </label>
                    <input 
                        id="sale_date"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="date" 
                        wire:model="sale_date"
                    />
                    @error('sale_date') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Customer Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="customer_name">
                        Customer Name
                    </label>
                    <input 
                        id="customer_name"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        wire:model="customer_name"
                        placeholder="Enter customer name"
                    />
                    @error('customer_name') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Customer Address -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="customer_address">
                        Customer Address
                    </label>
                    <textarea 
                        id="customer_address"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        wire:model="customer_address"
                        placeholder="Enter customer address"
                        rows="2"
                    ></textarea>
                    @error('customer_address') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Tax Fields -->
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            wire:model.live="is_taxable"
                            class="rounded border-gray-300 text-violet-600 shadow-sm focus:border-violet-300 focus:ring focus:ring-violet-200 focus:ring-opacity-50"
                        />
                        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Kena Pajak</span>
                    </label>
                </div>

                <!-- Tax fields that show/hide based on checkbox -->
                @if($is_taxable)
                <div class="md:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Tax Percentage -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="tax_percentage">
                                Total Pajak (%)
                            </label>
                            <input 
                                id="tax_percentage"
                                type="number" 
                                step="0.01"
                                min="0"
                                max="100"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                                wire:model.live="tax_percentage"
                                placeholder="11.00"
                            />
                            @error('tax_percentage') 
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Tax Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="tax_amount">
                                Total Nominal Pajak
                            </label>
                            <input 
                                id="tax_amount"
                                type="number" 
                                step="0.01"
                                min="0"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-600" 
                                wire:model="tax_amount"
                                placeholder="0"
                                readonly
                            />
                            <small class="text-gray-500">Terhitung otomatis: Rp {{ number_format($tax_amount, 2, ',', '.') }}</small>
                            @error('tax_amount') 
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Tax Summary -->
                    <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <div class="text-sm text-blue-800 dark:text-blue-200">
                            <strong>Ringkasan Pajak:</strong><br>
                            Total Penjualan: Rp {{ number_format($total_amount, 2, ',', '.') }}<br>
                            Pajak ({{ $tax_percentage }}%): Rp {{ number_format($tax_amount, 2, ',', '.') }}<br>
                            <strong>Total + Pajak: Rp {{ number_format($total_amount + $tax_amount, 2, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Sales Proof -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="sales_proof">
                        Sales Proof
                    </label>
                    <input 
                        id="sales_proof"
                        type="file" 
                        wire:model="sales_proof"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                    />
                    @if($isEditing && $sales_proof === null)
                        <div class="mt-2">
                            <small class="text-gray-500">Leave blank to keep existing proof</small>
                        </div>
                    @endif
                    @error('sales_proof') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeCreateModal" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="saveSalesModal" wire:loading.attr="disabled">
                {{ $isEditing ? 'Update' : 'Save' }} Sales Record
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Delete Confirmation Modal -->
    <x-confirmation-modal wire:model.live="showDeleteConfirmation">
        <x-slot name="title">
            {{ __('Delete Sales Record') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete the sales record ":name"?', ['name' => $deletingSaleName]) }}
            {{ __('Once the record is deleted, all of its data will be permanently removed.') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeDeleteConfirmation" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteSalesConfirmed" wire:loading.attr="disabled">
                {{ __('Delete Sales') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- Photo Preview Modal -->
    <x-dialog-modal wire:model.live="showPhotoModal" maxWidth="2xl">
        <x-slot name="title">
            Sales Proof Preview
        </x-slot>

        <x-slot name="content">
            @if($photoToView)
                <div class="flex justify-center">
                    <img src="{{ asset('storage/' . $photoToView) }}" alt="Sales Proof" class="max-w-full h-auto rounded-lg shadow-md">
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showPhotoModal', false)" wire:loading.attr="disabled">
                {{ __('Close') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>



    <!-- Form Section - Button to open modal -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-8">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">Sales Data Input</h2>
                <div class="flex space-x-3">
                    <!-- Export Section -->
                    <div class="flex items-center space-x-2">
                        <select 
                            wire:model.live="exportFilter"
                            class="px-7 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300 text-sm"
                        >
                            <option value="all">Semua Data</option>
                            <option value="taxable">Kena Pajak</option>
                            <option value="non_taxable">Tidak Kena Pajak</option>
                        </select>
                        <a 
                            href="{{ route('sales.export', ['filter' => $exportFilter]) }}"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors text-sm"
                        >
                            Export Excel
                        </a>
                    </div>
                    <button 
                        wire:click="openCreateModal"
                        class="px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                    >
                        Add Sales Record
                    </button>
                </div>
            </div>
        </header>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-8 p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
            <input 
                type="text" 
                wire:model.live="search"
                placeholder="Search by SP number or customer name"
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
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Sales Records</h2>
        </header>
        <div class="p-3">
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/30">
                            <th class="p-2 whitespace-nowrap">SP Number</th>
                            <th class="p-2 whitespace-nowrap">Date</th>
                            <th class="p-2 whitespace-nowrap">KG</th>
                            <th class="p-2 whitespace-nowrap">Price/KG</th>
                            <th class="p-2 whitespace-nowrap">Total Amount</th>
                            <th class="p-2 whitespace-nowrap">Pajak</th>
                            <th class="p-2 whitespace-nowrap">Total + Pajak</th>
                            <th class="p-2 whitespace-nowrap">Customer</th>
                            <th class="p-2 whitespace-nowrap">Sales Proof</th>
                            <th class="p-2 whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse($sales as $sale)
                            <tr>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left font-medium text-gray-800 dark:text-gray-100">{{ $sale->sp_number }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">{{ $sale->sale_date->format('d M Y') }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">{{ number_format($sale->kg_quantity, 2) }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">Rp {{ number_format($sale->price_per_kg, 2, ',', '.') }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">Rp {{ number_format($sale->total_amount, 2, ',', '.') }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">
                                        @if($sale->is_taxable)
                                            <span class="text-green-600 dark:text-green-400">
                                                {{ $sale->tax_percentage }}%<br>
                                                <small>Rp {{ number_format($sale->tax_amount, 2, ',', '.') }}</small>
                                            </span>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">Tidak</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left font-medium">
                                        Rp {{ number_format($sale->total_amount + ($sale->tax_amount ?? 0), 2, ',', '.') }}
                                    </div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">
                                        <div class="font-medium">{{ $sale->customer_name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $sale->customer_address }}</div>
                                    </div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    @if($sale->sales_proof_path)
                                        <button 
                                            wire:click="showPhoto('{{ $sale->sales_proof_path }}')"
                                            class="text-blue-600 hover:underline dark:text-blue-400"
                                        >
                                            View Proof
                                        </button>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">No proof</span>
                                    @endif
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <button 
                                            wire:click="openEditModal({{ $sale->id }})"
                                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm"
                                        >
                                            Edit
                                        </button>
                                        <button 
                                            wire:click="confirmDelete({{ $sale->id }}, '{{ $sale->sp_number }}')"
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
                                    No sales records found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        // Debug SP Number changes
        Livewire.on('updatedSpNumber', () => {
            console.log('SP Number updated');
        });
        
        Livewire.on('updatedKgQuantity', () => {
            console.log('KG Quantity updated');
        });
        
        Livewire.on('updatedPricePerKg', () => {
            console.log('Price per KG updated');
        });
        
        // Additional real-time calculation for better UX
        const pricePerKgInput = document.getElementById('price_per_kg');
        const kgQuantityInput = document.getElementById('kg_quantity');
        const totalAmountInput = document.getElementById('total_amount');
        
        if (pricePerKgInput && kgQuantityInput && totalAmountInput) {
            const calculateTotal = () => {
                const price = parseFloat(pricePerKgInput.value) || 0;
                const kg = parseFloat(kgQuantityInput.value) || 0;
                const total = price * kg;
                
                if (total > 0) {
                    totalAmountInput.value = total.toFixed(2);
                } else {
                    totalAmountInput.value = '';
                }
            };
            
            // Add input event listeners for immediate feedback
            pricePerKgInput.addEventListener('input', calculateTotal);
            kgQuantityInput.addEventListener('input', calculateTotal);
        }
    });
</script>