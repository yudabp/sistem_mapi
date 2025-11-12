<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Page header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Pengaturan</h1>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-8">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex flex-wrap -mb-px">
                <button 
                    wire:click="changeTab('vehicle-numbers')" 
                    class="py-4 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'vehicle-numbers' ? 'border-violet-500 text-violet-600 dark:text-violet-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                >
                    No Polisi
                </button>
                <button 
                    wire:click="changeTab('divisions')" 
                    class="py-4 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'divisions' ? 'border-violet-500 text-violet-600 dark:text-violet-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                >
                    Afdeling
                </button>
                <button 
                    wire:click="changeTab('pks')" 
                    class="py-4 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'pks' ? 'border-violet-500 text-violet-600 dark:text-violet-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                >
                    PKS
                </button>
                <button 
                    wire:click="changeTab('departments')" 
                    class="py-4 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'departments' ? 'border-violet-500 text-violet-600 dark:text-violet-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                >
                    Bagian
                </button>
                <button 
                    wire:click="changeTab('positions')" 
                    class="py-4 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'positions' ? 'border-violet-500 text-violet-600 dark:text-violet-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                >
                    Jabatan
                </button>
                <button 
                    wire:click="changeTab('family-compositions')" 
                    class="py-4 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'family-compositions' ? 'border-violet-500 text-violet-600 dark:text-violet-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                >
                    Susunan Keluarga
                </button>
                <button 
                    wire:click="changeTab('employment-statuses')" 
                    class="py-4 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'employment-statuses' ? 'border-violet-500 text-violet-600 dark:text-violet-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                >
                    Status Karyawan
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            @if($activeTab === 'vehicle-numbers')
                @livewire('vehicle-numbers')
            @elseif($activeTab === 'divisions')
                @livewire('divisions')
            @elseif($activeTab === 'pks')
                @livewire('pks')
            @elseif($activeTab === 'departments')
                @livewire('departments')
            @elseif($activeTab === 'positions')
                @livewire('positions')
            @elseif($activeTab === 'family-compositions')
                @livewire('family-compositions')
            @elseif($activeTab === 'employment-statuses')
                @livewire('employment-statuses')
            @endif
        </div>
    </div>
</div>
