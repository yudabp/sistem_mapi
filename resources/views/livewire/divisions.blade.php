<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Success Messages -->
        @if(session()->has('message'))
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-lg mb-6 dark:bg-emerald-500/10 dark:text-emerald-500">
                {{ session('message') }}
            </div>
        @endif

        <!-- Page header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Afdeling</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Master data afdeling perkebunan</p>
            </div>
            <button
                wire:click="openCreateModal"
                class="px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors flex items-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Afdeling
            </button>
        </div>

        <!-- Search Section -->
        <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-6 p-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Afdeling</label>
                    <input
                        type="text"
                        wire:model.live="search"
                        placeholder="Cari nama atau deskripsi afdeling"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                    />
                </div>
                <div class="flex items-end">
                    <button
                        wire:click="resetSearch"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                    >
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60 flex justify-between items-center">
                <div class="flex justify-between items-center w-full">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Daftar Afdeling</h2>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-700 dark:text-gray-400">Tampilkan per halaman:</span>
                        <select wire:model.live="perPage" class="text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="p-3">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full">
                        <thead>
                            <tr class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/30">
                                <th class="p-2 whitespace-nowrap text-left">Nama Afdeling</th>
                                <th class="p-2 whitespace-nowrap text-left">Deskripsi</th>
                                <th class="p-2 whitespace-nowrap text-left">Status</th>
                                <th class="p-2 whitespace-nowrap text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse($divisions as $division)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="font-medium text-gray-800 dark:text-gray-100">{{ $division->name }}</div>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="text-gray-600 dark:text-gray-400">{{ $division->description ?? '-' }}</div>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $division->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500' : 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500' }}">
                                            {{ $division->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="flex justify-center gap-2">
                                            <button
                                                wire:click="openEditModal({{ $division->id }})"
                                                class="px-3 py-1 bg-amber-600 text-white rounded hover:bg-amber-700 text-sm transition-colors"
                                                title="Edit Data"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            <button
                                                wire:click="confirmDelete({{ $division->id }})"
                                                class="px-3 py-1 bg-rose-600 text-white rounded hover:bg-rose-700 text-sm transition-colors"
                                                title="Hapus"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            <p class="text-lg font-medium">Tidak ada data afdeling</p>
                                            <p class="text-sm mt-1">Silakan tambahkan data afdeling terlebih dahulu</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

              
                <!-- Pagination Links -->
                @if($divisions && $divisions->hasPages())
                    <div class="mt-4 px-3">
                        {{ $divisions->links('pagination::livewire-tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <x-dialog-modal wire:model.live="showModal">
        <x-slot name="title">
            {{ $isEditing ? 'Edit Afdeling' : 'Tambah Afdeling' }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="saveDivision">
                @csrf
                <input type="hidden" wire:model="editing_id">

                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nama Afdeling <span class="text-red-500">*</span>
                        </label>
                        <input
                            wire:model="name"
                            type="text"
                            required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                            placeholder="Masukkan nama afdeling"
                        />
                        @error('name')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Deskripsi
                        </label>
                        <textarea
                            wire:model="description"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                            placeholder="Masukkan deskripsi (opsional)"
                        ></textarea>
                        @error('description')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-center">
                        <input
                            wire:model="is_active"
                            type="checkbox"
                            id="division_is_active"
                            class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 rounded"
                        />
                        <label for="division_is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Aktif
                        </label>
                    </div>
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-primary-button class="ms-3" wire:click="saveDivision" wire:loading.attr="disabled">
                {{ $isEditing ? 'Perbarui' : 'Simpan' }}
            </x-primary-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Delete Confirmation Modal -->
    <x-confirmation-modal wire:model.live="showDeleteConfirmation">
        <x-slot name="title">
            Hapus Afdeling
        </x-slot>

        <x-slot name="content">
            Apakah Anda yakin ingin menghapus data afdeling ini?
            <br><br>
            <strong>{{ $deletingDivisionName ?? '' }}</strong>
            <br><br>
            Tindakan ini tidak dapat dibatalkan.
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeDeleteConfirmation" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteDivision" wire:loading.attr="disabled">
                Hapus
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>