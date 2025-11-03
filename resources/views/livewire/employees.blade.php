<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Page header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Data Karyawan</h1>
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
                            <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 4v12c0 1.1-.9 2-2 2H6c-1.1 0-2-.9-2-2V8c0-1.1.9-2 2-2h1v-1c0-1.66 1.34-3 3-3s3 1.34 3 3v1h4c1.1 0 2 .9 2 2zm-8 9c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 mb-1">Total Karyawan</div>
                        <div class="flex items-baseline">
                            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $total_employees }}</div>
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
                            <path d="M11.8 10.9c-2.27-.59-3.06-1.24-3.06-2.08 0-1.05 1.02-1.67 2.71-1.67 1.66 0 2.45.66 2.45 1.72 0 .76-.42 1.21-1.6 1.51l-1.56.38c-.7.16-.96.44-.96.87 0 .52.39.83 1.2.83.52 0 .96-.16 1.26-.41.39-.31.45-.53.45-1.26V7.59c0-.22.16-.33.33-.33h1.03c.21 0 .33.13.33.33v.33c0 1.1-.66 1.72-1.5 2.02l-1.54.38c-.94.23-1.5.61-1.5 1.35 0 .76.63 1.22 1.71 1.22 1.31 0 2.05-.55 2.05-1.55 0-.65-.31-1.06-.77-1.22.45-.6.71-1.35.71-2.05v-.33c0-.22-.16-.33-.33-.33h-.98c-.21 0-.33.13-.33.33v.33c0 .51.28.84.69.93l1.57.39c.7.19 1.05.56 1.05 1.19 0 1.11-1.04 1.71-2.71 1.71-1.65 0-2.5-.64-2.5-1.81 0-.79.4-1.26 1.65-1.56l1.54-.38c.72-.18.98-.44.98-.88 0-.52-.4-.86-1.11-.86-.55 0-1.15.18-1.41.42-.41.3-.45.57-.45 1.28v.33c0 .22.16.33.33.33h1.08c.21 0 .33-.13.33-.33v-.33c0-1.1.69-1.71 1.5-2.02l1.54-.38c.95-.23 1.5-.59 1.5-1.35 0-.77-.66-1.22-1.71-1.22-1.31 0-2.05.54-2.05 1.55 0 .64.31 1.07.74 1.24-.46.57-.74 1.31-.74 2.03v.33c0 .22.16.33.33.33h.98c.21 0 .33-.13.33-.33v-.33c0-.52-.3-.84-.7-.93l-1.57-.39c-.67-.19-1.05-.55-1.05-1.18z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 mb-1">Total Gaji Bulanan</div>
                        <div class="flex items-baseline">
                            <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">Rp {{ number_format($total_salary, 2, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Data Modal -->
    <x-dialog-modal wire:model.live="showModal" maxWidth="2xl">
        <x-slot name="title">
            {{ $isEditing ? 'Edit Data Karyawan' : 'Tambah Data Karyawan' }}
        </x-slot>

        <x-slot name="content">


            <form wire:submit.prevent="saveEmployeeModal" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Employee ID (NDP) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="ndp">
                        ID Karyawan (NDP)
                    </label>
                    <input 
                        id="ndp"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        wire:model="ndp"
                        placeholder="Masukkan ID karyawan"
                        {{ $isEditing ? 'readonly' : '' }}
                    />
                    @error('ndp') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="name">
                        Nama
                    </label>
                    <input 
                        id="name"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        wire:model="name"
                        placeholder="Masukkan nama karyawan"
                    />
                    @error('name') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Department -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="department_id">
                        Department (Bagian)
                    </label>
                    <select
                        id="department_id"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                        wire:model="department_id"
                    >
                        <option value="">Pilih Bagian</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ $department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Position -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="position_id">
                        Position (Jabatan)
                    </label>
                    <select
                        id="position_id"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                        wire:model="position_id"
                    >
                        <option value="">Pilih Jabatan</option>
                        @foreach($positions as $pos)
                            <option value="{{ $pos->id }}" {{ $position_id == $pos->id ? 'selected' : '' }}>{{ $pos->name }}</option>
                        @endforeach
                    </select>
                    @error('position_id')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Grade -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="grade">
                        Golongan
                    </label>
                    <input 
                        id="grade"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        wire:model="grade"
                        placeholder="Masukkan golongan"
                    />
                </div>

                <!-- Family Composition -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="family_composition_id">
                        Family Composition (Susunan Keluarga)
                    </label>
                    <select
                        id="family_composition_id"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                        wire:model="family_composition_id"
                    >
                        <option value="">Pilih Susunan Keluarga</option>
                        @foreach($family_compositions as $fam_comp)
                            <option value="{{ $fam_comp->id }}" {{ $family_composition_id == $fam_comp->id ? 'selected' : '' }}>{{ $fam_comp->number }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Monthly Salary -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="monthly_salary">
                        Gaji Bulanan (Rp)
                    </label>
                    <input 
                        id="monthly_salary"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="number" 
                        step="0.01"
                        wire:model="monthly_salary"
                        placeholder="Masukkan gaji bulanan"
                    />
                    @error('monthly_salary') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="employment_status_id">
                        Employment Status (Status Karyawan)
                    </label>
                    <select
                        id="employment_status_id"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
                        wire:model="employment_status_id"
                    >
                        <option value="">Pilih Status Karyawan</option>
                        @foreach($employment_statuses as $emp_status)
                            <option value="{{ $emp_status->id }}" {{ $employment_status_id == $emp_status->id ? 'selected' : '' }}>{{ $emp_status->name }}</option>
                        @endforeach
                    </select>
                    @error('employment_status_id')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Hire Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="hire_date">
                        Tanggal Masuk (DD-MM-YYYY)
                    </label>
                    <input 
                        id="hire_date"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        placeholder="DD-MM-YYYY"
                        wire:model="hire_date"
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
                    @error('hire_date') 
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="address">
                        Alamat
                    </label>
                    <textarea 
                        id="address"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        wire:model="address"
                        placeholder="Masukkan alamat karyawan"
                        rows="2"
                    ></textarea>
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="phone">
                        Telepon
                    </label>
                    <input 
                        id="phone"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="text" 
                        wire:model="phone"
                        placeholder="Masukkan nomor telepon"
                    />
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="email">
                        Email
                    </label>
                    <input 
                        id="email"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300" 
                        type="email" 
                        wire:model="email"
                        placeholder="Masukkan alamat email"
                    />
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeCreateModal" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-button class="ms-3" wire:click="saveEmployeeModal" wire:loading.attr="disabled">
                {{ $isEditing ? 'Update' : 'Simpan' }} Data Karyawan
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Delete Confirmation Modal -->
    <x-confirmation-modal wire:model.live="showDeleteConfirmation">
        <x-slot name="title">
            Hapus Data Karyawan
        </x-slot>

        <x-slot name="content">
            Apakah Anda yakin ingin menghapus karyawan "{{ $deletingEmployeeName }}"?
            Setelah catatan dihapus, semua datanya akan dihapus secara permanen.
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeDeleteConfirmation" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteEmployeeConfirmed" wire:loading.attr="disabled">
                Hapus Karyawan
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- Form Section - Button to open modal -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-8">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100">Pendaftaran Karyawan</h2>
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
                                Upload Excel
                            </button>
                            <a 
                                href="{{ route('employees.sample.download') }}"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download Contoh
                            </a>
                        </div>
                    </div>
                    @endcanedit
                    
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
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Selesai</label>
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
                    
                    <!-- <button 
                        wire:click="openCreateModal"
                        class="px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors flex items-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Employee Record
                    </button> -->
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
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-8 p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pencarian</label>
            <input 
                type="text" 
                wire:model.live="search"
                placeholder="Cari berdasarkan nama, NDP, departemen, atau posisi"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
            />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter berdasarkan Departemen</label>
            <select
                wire:model.live="departmentFilter"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-gray-700 dark:text-gray-300"
            >
                <option value="">Semua Departemen</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Data Karyawan</h2>
        </header>
        <div class="p-3">
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/30">
                            <th class="p-2 whitespace-nowrap">NDP</th>
                            <th class="p-2 whitespace-nowrap">Nama</th>
                            <th class="p-2 whitespace-nowrap">Departemen</th>
                            <th class="p-2 whitespace-nowrap">Posisi</th>
                            <th class="p-2 whitespace-nowrap">Golongan</th>
                            <th class="p-2 whitespace-nowrap">Susunan Kel.</th>
                            <th class="p-2 whitespace-nowrap">Gaji</th>
                            <th class="p-2 whitespace-nowrap">Status</th>
                            @canedit
                            <th class="p-2 whitespace-nowrap">Aksi</th>
                            @else
                            <!-- <th class="p-2 whitespace-nowrap">--</th> -->
                            @endcanedit
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse($employees as $employee)
                            <tr>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left font-medium text-gray-800 dark:text-gray-100">{{ $employee->ndp }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">{{ $employee->name }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">{{ $employee->department_name }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">{{ $employee->position_name }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">{{ $employee->grade }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">{{ $employee->familyComposition?->number ?? $employee->family_composition }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="text-left">Rp {{ number_format($employee->monthly_salary, 2, ',', '.') }}</div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $employee->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500' :
                                           ($employee->status === 'inactive' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-500' :
                                           'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500') }}">
                                        {{ ucfirst($employee->status) }}
                                    </span>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        @canedit
                                        <button 
                                            wire:click="openEditModal({{ $employee->id }})"
                                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm"
                                        >
                                            Edit
                                        </button>
                                        <button 
                                            wire:click="confirmDelete({{ $employee->id }}, '{{ $employee->name }}')"
                                            class="px-3 py-1 bg-rose-600 text-white rounded hover:bg-rose-700 text-sm"
                                        >
                                            Delete
                                        </button>
                                        @else
                                        <!-- <div class="px-3 py-1 bg-gray-400 text-white rounded cursor-not-allowed text-sm">
                                            --
                                        </div> -->
                                        @endcanedit
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-2 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada data karyawan ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <x-dialog-modal wire:model.live="showImportModal" maxWidth="2xl">
        <x-slot name="title">
            Impor Data Karyawan dari Excel
        </x-slot>

        <x-slot name="content">
            <div class="mb-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Upload an Excel file to import employee data. The file should contain the following columns:
                </p>
                <ul class="mt-2 text-sm text-gray-600 dark:text-gray-400 list-disc list-inside">
                    <li><strong>ndp</strong> - ID Karyawan</li>
                    <li><strong>name</strong> - Nama karyawan</li>
                    <li><strong>department</strong> - Departemen</li>
                    <li><strong>position</strong> - Posisi</li>
                    <li><strong>grade</strong> - Golongan</li>
                    <li><strong>family_composition</strong> - Susunan keluarga</li>
                    <li><strong>monthly_salary</strong> - Gaji bulanan</li>
                    <li><strong>status</strong> - Status: active (aktif) atau inactive (tidak aktif)</li>
                    <li><strong>hire_date</strong> - Tanggal masuk</li>
                    <li><strong>address</strong> - Alamat</li>
                    <li><strong>phone</strong> - Nomor telepon</li>
                    <li><strong>email</strong> - Alamat email</li>
                </ul>
            </div>
            
            <form wire:submit.prevent="importEmployee" class="space-y-4">
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
                Batal
            </x-secondary-button>

            <x-button class="ms-3" wire:click="importEmployee" wire:loading.attr="disabled">
                Impor Data
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
