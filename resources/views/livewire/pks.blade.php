<div>
    @if(session()->has('message'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-lg mb-6 dark:bg-emerald-500/10 dark:text-emerald-500">
            {{ session('message') }}
        </div>
    @endif

    <!-- Form Section -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-8">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">
                {{ $editing_id ? 'Edit PKS' : 'Tambah PKS' }}
            </h2>
        </header>
        <div class="p-6 space-y-6">
            <form wire:submit.prevent="savePks" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="name">
                        Nama PKS
                    </label>
                    <input 
                        id="name"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        wire:model="name"
                        placeholder="Masukkan nama PKS"
                    />
                    @error('name') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="description">
                        Deskripsi
                    </label>
                    <input 
                        id="description"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        wire:model="description"
                        placeholder="Masukkan deskripsi (opsional)"
                    />
                    @error('description') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="flex items-center">
                    <input 
                        id="is_active"
                        type="checkbox" 
                        wire:model="is_active"
                        class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 rounded"
                    />
                    <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                        Aktif
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="md:col-span-2">
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                    >
                        {{ $editing_id ? 'Perbarui' : 'Simpan' }} PKS
                    </button>
                    
                    @if($editing_id)
                    <button 
                        type="button"
                        wire:click="resetForm"
                        class="ml-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
                    >
                        Batal
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Search Section -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-8 p-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari</label>
            <input 
                type="text" 
                wire:model.live="search"
                placeholder="Cari PKS atau deskripsi"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
            />
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Daftar PKS</h2>
        </header>
        <div class="p-3">
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/30">
                            <th class="p-2 whitespace-nowrap">Nama PKS</th>
                            <th class="p-2 whitespace-nowrap">Deskripsi</th>
                            <th class="p-2 whitespace-nowrap">Status</th>
                            <th class="p-2 whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse($pks_list as $pks_item)
                            <tr>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left font-medium text-gray-800 dark:text-gray-100">{{ $pks_item->name }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">{{ $pks_item->description ?? '-' }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $pks_item->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500' : 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500' }}">
                                        {{ $pks_item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <button 
                                        wire:click="editPks({{ $pks_item->id }})"
                                        class="px-3 py-1 bg-amber-600 text-white rounded hover:bg-amber-700 text-sm mr-2"
                                    >
                                        Edit
                                    </button>
                                    <button 
                                        wire:click="deletePks({{ $pks_item->id }})"
                                        class="px-3 py-1 bg-rose-600 text-white rounded hover:bg-rose-700 text-sm"
                                        onclick="confirm('Yakin ingin menghapus?') || event.stopImmediatePropagation()"
                                    >
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-2 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada data PKS
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
