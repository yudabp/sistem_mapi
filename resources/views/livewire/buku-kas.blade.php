<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Page header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Buku Kas</h1>
    </div>

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
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 mb-1">Total Pemasukan</div>
                        <div class="flex items-baseline">
                            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">Rp {{ number_format($total_income, 2, ',', '.') }}</div>
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
                            <path d="M11.8 10.9c-2.27-.59-3.06-1.24-3.06-2.08 0-1.05 1.02-1.67 2.71-1.67 1.66 0 2.45.66 2.45 1.72 0 .76-.42 1.21-1.6 1.51l-1.56.38c-.7.16-.96.44-.96.87 0 .52.39.83 1.2.83.52 0 .96-.16 1.26-.41.39-.31.45-.53.45-1.22V7.59c0-.22.16-.33.33-.33h1.03c.21 0 .33.13.33.33v.33c0 1.1-.66 1.72-1.5 2.02l-1.54.38c-.94.23-1.5.61-1.5 1.35 0 .76.63 1.22 1.71 1.22 1.31 0 2.05-.55 2.05-1.55 0-.65-.31-1.06-.77-1.22.45-.6.71-1.35.71-2.05v-.33c0-.22-.16-.33-.33-.33h-.98c-.21 0-.33.13-.33.33v.33c0 .51.28.84.69.93l1.57.39c.7.19 1.05.56 1.05 1.19 0 1.11-1.04 1.71-2.71 1.71-1.65 0-2.5-.64-2.5-1.81 0-.79.4-1.26 1.65-1.56l1.54-.38c.72-.18.98-.44.98-.88 0-.52-.4-.86-1.11-.86-.55 0-1.15.18-1.41.42-.41.3-.45.57-.45 1.28v.33c0 .22.16.33.33.33h1.08c.21 0 .33-.13.33-.33v-.33c0-1.1.69-1.71 1.5-2.02l1.54-.38c.95-.23 1.5-.59 1.5-1.35 0-.77-.66-1.22-1.71-1.22-1.31 0-2.05.54-2.05 1.55 0 .64.31 1.07.74 1.24-.46.57-.74 1.31-.74 2.03v.33c0 .22.16.33.33.33h.98c.21 0 .33-.13.33-.33v-.33c0-.52-.3-.84-.7-.93l-1.57-.39c-.67-.19-1.05-.55-1.05-1.18z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 mb-1">Total Pengeluaran</div>
                        <div class="flex items-baseline">
                            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">Rp {{ number_format($total_expenses, 2, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm p-5">
            <div class="flex justify-between items-start">
                <div class="flex items-center">
                    <div class="bg-violet-100 dark:bg-violet-500/30 p-3 rounded-lg mr-4">
                        <svg class="w-6 h-6 fill-current text-violet-500 dark:text-violet-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M11.8 10.9c-2.27-.59-3.06-1.24-3.06-2.08 0-1.05 1.02-1.67 2.71-1.67 1.66 0 2.45.66 2.45 1.72 0 .76-.42 1.21-1.6 1.51l-1.56.38c-.7.16-.96.44-.96.87 0 .52.39.83 1.2.83.52 0 .96-.16 1.26-.41.39-.31.45-.53.45-1.22V7.59c0-.22.16-.33.33-.33h1.03c.21 0 .33.13.33.33v.33c0 1.1-.66 1.72-1.5 2.02l-1.54.38c-.94.23-1.5.61-1.5 1.35 0 .76.63 1.22 1.71 1.22 1.31 0 2.05-.55 2.05-1.55 0-.65-.31-1.06-.77-1.22.45-.6.71-1.35.71-2.05v-.33c0-.22-.16-.33-.33-.33h-.98c-.21 0-.33.13-.33.33v.33c0 .51.28.84.69.93l1.57.39c.7.19 1.05.56 1.05 1.19 0 1.11-1.04 1.71-2.71 1.71-1.65 0-2.5-.64-2.5-1.81 0-.79.4-1.26 1.65-1.56l1.54-.38c.72-.18.98-.44.98-.88 0-.52-.4-.86-1.11-.86-.55 0-1.15.18-1.41.42-.41.3-.45.57-.45 1.28v.33c0 .22.16.33.33.33h1.08c.21 0 .33-.13.33-.33v-.33c0-1.1.69-1.71 1.5-2.02l1.54-.38c.95-.23 1.5-.59 1.5-1.35 0-.77-.66-1.22-1.71-1.22-1.31 0-2.05.54-2.05 1.55 0 .64.31 1.07.74 1.24-.46.57-.74 1.31-.74 2.03v.33c0 .22.16.33.33.33h.98c.21 0 .33-.13.33-.33v-.33c0-.52-.3-.84-.7-.93l-1.57-.39c-.67-.19-1.05-.55-1.05-1.18z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 mb-1">Saldo</div>
                        <div class="flex items-baseline">
                            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">Rp {{ number_format($balance, 2, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-8">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Input Transaksi Kas</h2>
        </header>
        <div class="p-6 space-y-6">
            @if(session()->has('message'))
                <div class="bg-emerald-50 text-emerald-700 p-4 rounded-lg dark:bg-emerald-500/10 dark:text-emerald-500">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="saveTransaction" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Transaction Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="transaction_date">
                        Tanggal Transaksi
                    </label>
                    <input 
                        id="transaction_date"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="date" 
                        wire:model="transaction_date"
                        value="{{ date('Y-m-d') }}"
                    />
                    @error('transaction_date') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Transaction Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="transaction_type">
                        Jenis Transaksi
                    </label>
                    <select 
                        id="transaction_type"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        wire:model="transaction_type"
                    >
                        <option value="income">Pemasukan</option>
                        <option value="expense">Pengeluaran</option>
                    </select>
                    @error('transaction_type') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="amount">
                        Jumlah (Rp)
                    </label>
                    <input 
                        id="amount"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="number" 
                        step="0.01"
                        wire:model="amount"
                        placeholder="Masukkan jumlah"
                    />
                    @error('amount') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Purpose -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="purpose">
                        Tujuan
                    </label>
                    <input 
                        id="purpose"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        wire:model="purpose"
                        placeholder="Masukkan tujuan transaksi"
                    />
                    @error('purpose') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="description">
                        Deskripsi
                    </label>
                    <textarea 
                        id="description"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        wire:model="description"
                        placeholder="Masukkan deskripsi lengkap"
                        rows="2"
                    ></textarea>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="notes">
                        Catatan
                    </label>
                    <textarea 
                        id="notes"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        wire:model="notes"
                        placeholder="Masukkan catatan tambahan"
                        rows="2"
                    ></textarea>
                </div>

                <!-- Proof Document -->
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="proof_document">
                        Bukti Dokumen
                    </label>
                    <input 
                        id="proof_document"
                        type="file" 
                        wire:model="proof_document"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                    />
                    @error('proof_document') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="md:col-span-2 lg:col-span-3">
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                    >
                        Simpan Transaksi Kas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-8 p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pencarian</label>
            <input 
                type="text" 
                wire:model.live="search"
                placeholder="Cari berdasarkan tujuan atau deskripsi"
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
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Transaksi Buku Kas</h2>
        </header>
        <div class="p-3">
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/30">
                            <th class="p-2 whitespace-nowrap">Tanggal</th>
                            <th class="p-2 whitespace-nowrap">No. Transaksi</th>
                            <th class="p-2 whitespace-nowrap">Jenis</th>
                            <th class="p-2 whitespace-nowrap">Tujuan</th>
                            <th class="p-2 whitespace-nowrap">Jumlah</th>
                            <th class="p-2 whitespace-nowrap">Bukti</th>
                            <th class="p-2 whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse($transactions as $transaction)
                            <tr>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">{{ $transaction->transaction_date->format('d M Y') }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left font-medium text-gray-800 dark:text-gray-100">{{ $transaction->transaction_number }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $transaction->transaction_type === 'income' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-800/30 dark:text-emerald-500' : 
                                           'bg-rose-100 text-rose-800 dark:bg-rose-800/30 dark:text-rose-500' }}">
                                        {{ $transaction->transaction_type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                    </span>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left font-medium">{{ $transaction->source_destination }}</div>
                                    @if($transaction->notes)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->notes }}</div>
                                    @endif
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left font-medium {{ $transaction->transaction_type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                        {{ $transaction->transaction_type === 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 2, ',', '.') }}
                                    </div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    @if($transaction->proof_document_path)
                                        <a href="{{ Storage::url($transaction->proof_document_path) }}" target="_blank" class="text-blue-600 hover:underline dark:text-blue-400">
                                            Lihat Dokumen
                                        </a>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">Tidak ada bukti</span>
                                    @endif
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    @if($transaction->status === 'unpaid')
                                        <button 
                                            wire:click="markAsPaid({{ $transaction->id }})"
                                            class="px-3 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-700 text-sm mr-2"
                                        >
                                            Tandai Lunas
                                        </button>
                                    @endif
                                    <button 
                                        wire:click="deleteTransaction({{ $transaction->id }})"
                                        class="px-3 py-1 bg-rose-600 text-white rounded hover:bg-rose-700 text-sm"
                                        onclick="confirm('Apakah Anda yakin?') || event.stopImmediatePropagation()"
                                    >
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-2 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada transaksi buku kas ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
