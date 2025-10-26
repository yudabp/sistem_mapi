<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Page header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Buku Kas Kebun (BKK)</h1>
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

    <!-- Form Section - Button to open modal -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-8">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">Buku Kas Kebun</h2>
                <!-- <button 
                    wire:click="openCreateModal"
                    class="px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                >
                    Add Transaction
                </button> -->
                @canedit
                    <button
                        wire:click="openCreateModal"
                        class="px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors flex items-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Record
                    </button>
                @else
                    <!-- <div class="px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Record
                    </div> -->
                @endcanedit
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
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Buku Kas Kebun Transactions</h2>
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
                            <th class="p-2 whitespace-nowrap">KP Reference</th>
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
                                            View Document
                                        </button>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">No proof</span>
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
                                            Edit
                                        </button>
                                        <button 
                                            wire:click="confirmDelete({{ $transaction->id }}, '{{ $transaction->transaction_number }}')"
                                            class="px-3 py-1 bg-rose-600 text-white rounded hover:bg-rose-700 text-sm"
                                        >
                                            Delete
                                        </button>
                                        @else
                                        <!-- <button 
                                            class="px-3 py-1 bg-gray-400 text-white rounded cursor-not-allowed text-sm"
                                            title="You do not have permission to edit"
                                        >
                                            Edit
                                        </button>
                                        <button 
                                            class="px-3 py-1 bg-gray-400 text-white rounded cursor-not-allowed text-sm"
                                            title="You do not have permission to delete"
                                        >
                                            Delete
                                        </button> -->
                                        @endcanedit
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-2 text-center text-gray-500 dark:text-gray-400">
                                    No Buku Kas Kebun transactions found
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
            <form wire:submit.prevent="saveTransactionModal" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Transaction Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="transaction_date">
                        Transaction Date
                    </label>
                    <input 
                        id="transaction_date"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="date" 
                        wire:model="transaction_date"
                        {{ $isEditing ? '' : 'value=' . date('Y-m-d') }}
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
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        wire:model="transaction_type"
                    >
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
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
                        Amount (Rp)
                    </label>
                    <input 
                        id="amount"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
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
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
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
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        wire:model="received_by"
                        placeholder="Enter name of person who received"
                    />
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="category">
                        Category
                    </label>
                    <input 
                        id="category"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        wire:model="category"
                        placeholder="Enter transaction category"
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
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        wire:model="kp_id"
                    >
                        <option value="">Select KP Transaction</option>
                        @foreach(\App\Models\KeuanganPerusahaan::orderBy('transaction_date', 'desc')->get() as $kp)
                            <option value="{{ $kp->id }}">{{ $kp->transaction_number }} - {{ $kp->source_destination }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="notes">
                        Notes
                    </label>
                    <textarea 
                        id="notes"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        wire:model="notes"
                        placeholder="Enter additional notes"
                        rows="2"
                    ></textarea>
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
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
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
            {{ __('Delete Buku Kas Kebun Transaction') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete the Buku Kas Kebun transaction ":name"?', ['name' => $deletingTransactionName]) }}
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
                                <span class="text-sm text-gray-600 dark:text-gray-400">Category:</span>
                                <span class="text-sm text-gray-800 dark:text-gray-100">{{ $relatedKpTransaction->category }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Source/Destination:</span>
                                <span class="text-sm text-gray-800 dark:text-gray-100">{{ $relatedKpTransaction->source_destination }}</span>
                            </div>
                            @if($relatedKpTransaction->received_by)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Received By:</span>
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
                                <span class="text-sm text-gray-600 dark:text-gray-400">Category:</span>
                                <span class="text-sm text-gray-800 dark:text-gray-100">{{ \App\Models\BukuKasKebun::find($selectedBkkId)?->category }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Source/Destination:</span>
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