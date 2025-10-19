<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Page header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Data Penjualan</h1>
    </div>

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
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 mb-1">Total KG Sold</div>
                        <div class="flex items-baseline">
                            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ number_format($total_kg, 2) }} KG</div>
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
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 mb-1">Total Sales</div>
                        <div class="flex items-baseline">
                            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">Rp {{ number_format($total_sales, 2, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-8">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Sales Data Input</h2>
        </header>
        <div class="p-6 space-y-6">
            @if(session()->has('message'))
                <div class="bg-emerald-50 text-emerald-700 p-4 rounded-lg dark:bg-emerald-500/10 dark:text-emerald-500">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="saveSales" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- SP Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="sp_number">
                        SP Number
                    </label>
                    <select
                        id="sp_number"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                        wire:model="sp_number"
                        wire:change="$dispatch('spChanged')"
                    >
                        <option value="">Select SP Number</option>
                        @foreach($sp_numbers as $sp)
                            <option value="{{ $sp->sp_number }}">
                                {{ $sp->sp_number }}
                            </option>
                        @endforeach
                    </select>
                    @error('sp_number')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- TBS Quantity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="tbs_quantity">
                        TBS Quantity (KG)
                    </label>
                    <input
                        id="tbs_quantity"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300 {{ $is_fields_disabled ? 'bg-gray-100 dark:bg-gray-600' : '' }}"
                        type="number"
                        step="0.01"
                        wire:model="tbs_quantity"
                        placeholder="Auto-filled from SP"
                        {{ $is_fields_disabled ? 'readonly' : '' }}
                    />
                </div>

                <!-- KG Quantity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="kg_quantity">
                        KG Quantity
                    </label>
                    <input
                        id="kg_quantity"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300 {{ $is_fields_disabled ? 'bg-gray-100 dark:bg-gray-600' : '' }}"
                        type="number"
                        step="0.01"
                        wire:model="kg_quantity"
                        placeholder="Auto-filled from SP"
                        {{ $is_fields_disabled ? 'readonly' : '' }}
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
                        Customer Name <span class="text-gray-400 text-xs">(Optional)</span>
                    </label>
                    <input 
                        id="customer_name"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        wire:model="customer_name"
                        placeholder="Enter customer name (optional)"
                    />
                    @error('customer_name') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Customer Address -->
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="customer_address">
                        Customer Address <span class="text-gray-400 text-xs">(Optional)</span>
                    </label>
                    <textarea 
                        id="customer_address"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        wire:model="customer_address"
                        placeholder="Enter customer address (optional)"
                        rows="2"
                    ></textarea>
                    @error('customer_address') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Sales Proof -->
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="sales_proof">
                        Sales Proof
                    </label>
                    <input 
                        id="sales_proof"
                        type="file" 
                        wire:model="sales_proof"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                    />
                    @error('sales_proof') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="md:col-span-2 lg:col-span-3">
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                    >
                        Save Sales Record
                    </button>
                </div>
            </form>
        </div>
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
                                        <div class="font-medium">{{ $sale->customer_name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $sale->customer_address }}</div>
                                    </div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    @if($sale->sales_proof_path)
                                        <button
                                            wire:click="$dispatch('showPhoto', {{
                                                json_encode([
                                                    'url' => Storage::url($sale->sales_proof_path),
                                                    'title' => 'Sales Proof - ' . $sale->sp_number
                                                ])
                                            }})"
                                            class="text-blue-600 hover:underline dark:text-blue-400"
                                        >
                                            View Proof
                                        </button>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">No proof</span>
                                    @endif
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <button 
                                        wire:click="deleteSales({{ $sale->id }})"
                                        class="px-3 py-1 bg-rose-600 text-white rounded hover:bg-rose-700 text-sm"
                                        onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                    >
                                        Delete
                                    </button>
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
    // Auto-calculate total amount when kg quantity or price per kg changes
    document.addEventListener('livewire:init', () => {
        Livewire.on('updatedKgQuantity', () => {
            // This will be handled by the backend
        });
        
        Livewire.on('updatedPricePerKg', () => {
            // This will be handled by the backend
        });
    });
</script>
