<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Page header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Manajemen User</h1>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm p-5">
            <div class="flex justify-between items-start">
                <div class="flex items-center">
                    <div class="bg-violet-100 dark:bg-violet-500/30 p-3 rounded-lg mr-4">
                        <svg class="w-6 h-6 fill-current text-violet-500 dark:text-violet-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 4v12c0 1.1-.9 2-2 2H6c-1.1 0-2-.9-2-2V8c0-1.1.9-2 2-2h1v-1c0-1.66 1.34-3 3-3s3 1.34 3 3v1h4c1.1 0 2 .9 2 2zm-8 9c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 mb-1">Total User</div>
                        <div class="flex items-baseline">
                            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $users->total() }}</div>
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
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 mb-1">Total Peran</div>
                        <div class="flex items-baseline">
                            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $roles->count() }}</div>
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
                            <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 mb-1">Aktif Saat Ini</div>
                        <div class="flex items-baseline">
                            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $users->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Management Modal -->
    <x-dialog-modal wire:model.live="showModal" maxWidth="2xl">
        <x-slot name="title">
            {{ $isEditMode ? 'Edit User' : 'Tambah User Baru' }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="space-y-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="name">
                        Nama Lengkap
                    </label>
                    <input 
                        id="name"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        wire:model="name"
                        placeholder="Masukkan nama lengkap"
                    />
                    @error('name') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="email">
                        Alamat Email
                    </label>
                    <input 
                        id="email"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="email" 
                        wire:model="email"
                        placeholder="Masukkan alamat email"
                    />
                    @error('email') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="password">
                        {{ $isEditMode ? 'Password (kosongkan untuk tetap sama)' : 'Password' }}
                    </label>
                    <input 
                        id="password"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="password" 
                        wire:model="password"
                        placeholder="{{ $isEditMode ? 'Kosongkan untuk tetap sama' : 'Masukkan password' }}"
                    />
                    @error('password') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="role">
                        Peran
                    </label>
                    <select 
                        id="role"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        wire:model="role"
                    >
                        <option value="">Pilih peran</option>
                        @foreach($roles as $roleItem)
                            <option value="{{ $roleItem->name }}">{{ ucfirst($roleItem->name) }}</option>
                        @endforeach
                    </select>
                    @error('role') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="cancelEdit" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-button class="ms-3" wire:click="save" wire:loading.attr="disabled">
                {{ $isEditMode ? 'Update User' : 'Buat User' }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Delete Confirmation Modal -->
    <x-confirmation-modal wire:model.live="showDeleteConfirmation">
        <x-slot name="title">
            Hapus User
        </x-slot>

        <x-slot name="content">
            Apakah Anda yakin ingin menghapus user ":name"?
            Setelah user dihapus, semua data mereka akan dihapus secara permanen.
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="cancelEdit" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteConfirmed" wire:loading.attr="disabled">
                Hapus User
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- Form Section - Button to open modal -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-8">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">Manajemen User</h2>
                <button 
                    wire:click="$set('showModal', true); $set('isEditMode', false)"
                    class="px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah User Baru
                </button>
            </div>
        </header>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-8 p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari User</label>
            <input 
                type="text" 
                wire:model.live="search"
                placeholder="Cari berdasarkan nama atau email"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
            />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter berdasarkan Peran</label>
            <select 
                wire:model.live="roleFilter"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
            >
                <option value="all">Semua Peran</option>
                @foreach($roles as $roleItem)
                    <option value="{{ $roleItem->name }}">{{ ucfirst($roleItem->name) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Daftar User</h2>
        </header>
        <div class="p-3">
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/30">
                            <th class="p-2 whitespace-nowrap">Nama</th>
                            <th class="p-2 whitespace-nowrap">Email</th>
                            <th class="p-2 whitespace-nowrap">Peran</th>
                            <th class="p-2 whitespace-nowrap">Dibuat Pada</th>
                            <th class="p-2 whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse($users as $user)
                            <tr>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="mr-3">
                                            <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D8ABC&color=fff" alt="{{ $user->name }}" />
                                        </div>
                                        <div class="font-medium text-gray-800 dark:text-gray-100">{{ $user->name }}</div>
                                    </div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">{{ $user->email }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($user->roles as $userRole)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500">
                                                {{ ucfirst($userRole->name) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left text-gray-600 dark:text-gray-400">{{ $user->created_at->format('d M Y') }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <button 
                                            wire:click="edit({{ $user->id }})"
                                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm"
                                        >
                                            Edit
                                        </button>
                                        <button 
                                            wire:click="confirmDelete({{ $user->id }}, '{{ $user->name }}')"
                                            class="px-3 py-1 bg-rose-600 text-white rounded hover:bg-rose-700 text-sm"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-2 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada user yang ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-4 flex justify-between items-center">
                <div class="text-sm text-gray-700 dark:text-gray-400">
                    Menampilkan {{ $users->firstItem() }} hingga {{ $users->lastItem() }} dari {{ $users->total() }} hasil
                </div>
                <div class="flex space-x-1">
                    @if($users->onFirstPage())
                        <button class="px-3 py-1 text-sm bg-gray-100 text-gray-400 rounded cursor-not-allowed" disabled>
                            Sebelumnya
                        </button>
                    @else
                        <button wire:click="setPage('{{ $users->previousPageUrl() }}')" class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                            Sebelumnya
                        </button>
                    @endif

                    @if($users->hasMorePages())
                        <button wire:click="setPage('{{ $users->nextPageUrl() }}')" class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                            Selanjutnya
                        </button>
                    @else
                        <button class="px-3 py-1 text-sm bg-gray-100 text-gray-400 rounded cursor-not-allowed" disabled>
                            Selanjutnya
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
