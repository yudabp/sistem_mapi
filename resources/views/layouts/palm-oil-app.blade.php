<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - PT API APPS</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles        
    </head>
    <body
        class="font-inter antialiased bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400"
        :class="{ 'sidebar-expanded': sidebarExpanded }"
        x-data="{ sidebarOpen: false, sidebarExpanded: localStorage.getItem('sidebar-expanded') == 'true' }"
        x-init="$watch('sidebarExpanded', value => localStorage.setItem('sidebar-expanded', value))"    
    >
        <!-- Page wrapper -->
        <div class="flex h-[100dvh] overflow-hidden">

            <!-- Sidebar -->
            <div class="min-w-fit">
                <!-- Sidebar backdrop (mobile only) -->
                <div
                    class="fixed inset-0 bg-gray-900/30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
                    :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'"
                    aria-hidden="true"
                    x-cloak
                ></div>

                <!-- Sidebar -->
                <div
                    id="sidebar"
                    class="flex lg:flex! flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-[100dvh] overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:w-64! shrink-0 bg-white dark:bg-gray-800 p-4 transition-all duration-200 ease-in-out rounded-r-2xl shadow-xs border-r border-gray-200 dark:border-gray-700/60"
                    :class="sidebarOpen ? 'max-lg:translate-x-0' : 'max-lg:-translate-x-64'"
                    @click.outside="sidebarOpen = false"
                    @keydown.escape.window="sidebarOpen = false"
                >
                    <!-- Sidebar header -->
                    <div class="flex justify-between mb-10 pr-3 sm:px-2">
                        <!-- Close button -->
                        <button class="lg:hidden text-gray-500 hover:text-gray-400" @click.stop="sidebarOpen = !sidebarOpen" aria-controls="sidebar" :aria-expanded="sidebarOpen">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
                            </svg>
                        </button>
                        <!-- Logo -->
                        <a class="block" href="{{ route('dashboard') }}">
                            <svg class="fill-violet-500" xmlns="http://www.w3.org/2000/svg" width="32" height="32">
                                <path d="M31.956 14.8C31.372 6.92 25.08.628 17.2.044V5.76a9.04 9.04 0 0 0 9.04 9.04h5.716ZM14.8 26.24v5.716C6.92 31.372.63 25.08.044 17.2H5.76a9.04 9.04 0 0 1 9.04 9.04Zm11.44-9.04h5.716c-.584 7.88-6.876 14.172-14.756 14.756V26.24a9.04 9.04 0 0 1 9.04-9.04ZM.044 14.8C.63 6.92 6.92.628 14.8.044V5.76a9.04 9.04 0 0 1-9.04 9.04H.044Z" />
                            </svg>                
                        </a>
                    </div>

                    <!-- Links -->
                    <div class="space-y-8">
                        <!-- Palm Oil Management group -->
                        <div>
                            <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                                <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6" aria-hidden="true">•••</span>
                                <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Main Menu</span>
                            </h3>
                            <ul class="mt-3">
                                <!-- Dashboard -->
                                <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ Request::is('dashboard') ? 'bg-linear-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                                    <a class="block text-gray-800 dark:text-gray-100 truncate transition {{ Request::is('dashboard') ? '' : 'hover:text-gray-900 dark:hover:text-white' }}" href="{{ route('dashboard') }}">
                                        <div class="flex items-center">
                                            <svg class="shrink-0 fill-current {{ Request::is('dashboard') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                                <path d="M5.936.278A7.983 7.983 0 0 1 8 0a8 8 0 1 1-8 8c0-.722.104-1.413.278-2.064a1 1 0 1 1 1.932.516A5.99 5.99 0 0 0 2 8a6 6 0 1 0 6-6c-.53 0-1.045.076-1.548.21A1 1 0 1 1 5.936.278Z" />
                                                <path d="M6.068 7.482A2.003 2.003 0 0 0 8 10a2 2 0 1 0-.518-3.932L3.707 2.293a1 1 0 0 0-1.414 1.414l3.775 3.775Z" />
                                            </svg>
                                            <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Dashboards</span>
                                        </div>
                                    </a>
                                </li>
                                
                                <!-- Data Produksi -->
                                <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ Request::is('production') || Request::is('data-produksi') ? 'bg-linear-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                                    <a class="block text-gray-800 dark:text-gray-100 truncate transition {{ (Request::is('production') || Request::is('data-produksi')) ? '' : 'hover:text-gray-900 dark:hover:text-white' }}" href="{{ route('data-produksi') }}">
                                        <div class="flex items-center">
                                            <svg class="shrink-0 fill-current {{ (Request::is('production') || Request::is('data-produksi')) ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                                <path d="M5 10h2v10H5V10zm4-2h2v12H9V8zm4 6h2v6h-2v-6z"/>
                                            </svg>
                                            <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Data Produksi</span>
                                        </div>
                                    </a>
                                </li>
                                
                                <!-- Data Penjualan -->
                                <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ Request::is('sales') || Request::is('data-penjualan') ? 'bg-linear-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                                    <a class="block text-gray-800 dark:text-gray-100 truncate transition {{ (Request::is('sales') || Request::is('data-penjualan')) ? '' : 'hover:text-gray-900 dark:hover:text-white' }}" href="{{ route('data-penjualan') }}">
                                        <div class="flex items-center">
                                            <svg class="shrink-0 fill-current {{ (Request::is('sales') || Request::is('data-penjualan')) ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                            </svg>
                                            <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Data Penjualan</span>
                                        </div>
                                    </a>
                                </li>
                                
                                <!-- Keuangan Perusahaan -->
                                <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0" x-data="{ open: {{ (Request::is('financial*') || Request::is('keuangan-perusahaan') || Request::is('buku-kas-kebun') || Request::is('data-hutang')) ? 1 : 0 }} }" x-init="if ('{{ (Request::is('financial*') || Request::is('keuangan-perusahaan') || Request::is('buku-kas') || Request::is('data-hutang')) }}' == '1') open = true">
                                    <a class="block text-gray-800 dark:text-gray-100 truncate transition {{ (Request::is('financial*') || Request::is('keuangan-perusahaan') || Request::is('buku-kas-kebun') || Request::is('buku-kas-kebun') || Request::is('data-hutang')) ? '' : 'hover:text-gray-900 dark:hover:text-white' }}" href="#" @click.prevent="open = !open; sidebarExpanded = true">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <svg class="shrink-0 fill-current {{ (Request::is('financial*') || Request::is('keuangan-perusahaan') || Request::is('buku-kas-kebun') || Request::is('buku-kas-kebun') || Request::is('data-hutang')) ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                                    <path d="M11.8 10.9c-2.27-.59-3.06-1.24-3.06-2.08 0-1.05 1.02-1.67 2.71-1.67 1.66 0 2.45.66 2.45 1.72 0 .76-.42 1.21-1.6 1.51l-1.56.38c-.7.16-.96.44-.96.87 0 .52.39.83 1.2.83.52 0 .96-.16 1.26-.41.39-.31.45-.53.45-1.22V7.59c0-.22.16-.33.33-.33h1.03c.21 0 .33.13.33.33v.33c0 1.1-.66 1.72-1.5 2.02l-1.54.38c-.94.23-1.5.61-1.5 1.35 0 .76.63 1.22 1.71 1.22 1.31 0 2.05-.55 2.05-1.55 0-.65-.31-1.06-.77-1.22.45-.6.71-1.35.71-2.05v-.33c0-.22-.16-.33-.33-.33h-.98c-.21 0-.33.13-.33.33v.33c0 .51.28.84.69.93l1.57.39c.7.19 1.05.56 1.05 1.19 0 1.11-1.04 1.71-2.71 1.71-1.65 0-2.5-.64-2.5-1.81 0-.79.4-1.26 1.65-1.56l1.54-.38c.72-.18.98-.44.98-.88 0-.52-.4-.86-1.11-.86-.55 0-1.15.18-1.41.42-.41.3-.45.57-.45 1.28v.33c0 .22.16.33.33.33h1.08c.21 0 .33-.13.33-.33v-.33c0-1.1.69-1.71 1.5-2.02l1.54-.38c.95-.23 1.5-.59 1.5-1.35 0-.77-.66-1.22-1.71-1.22-1.31 0-2.05.54-2.05 1.55 0 .64.31 1.07.74 1.24-.46.57-.74 1.31-.74 2.03v.33c0 .22.16.33.33.33h.98c.21 0 .33-.13.33-.33v-.33c0-.52-.3-.84-.7-.93l-1.57-.39c-.67-.19-1.05-.55-1.05-1.18z"/>
                                                </svg>
                                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Keuangan Perusahaan</span>
                                            </div>
                                            <!-- Icon -->
                                            <div class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                                <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500 {{ (Request::is('financial*') || Request::is('keuangan-perusahaan') || Request::is('buku-kas-kebun') || Request::is('buku-kas-kebun') || Request::is('data-hutang')) ? 'rotate-180' : 'rotate-0' }}" :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                                    <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                        <ul class="pl-8 mt-1 {{ (Request::is('financial*') || Request::is('keuangan-perusahaan') || Request::is('buku-kas-kebun') || Request::is('buku-kas-kebun') || Request::is('data-hutang')) ? 'hidden' : 'block' }}" :class="open ? 'block!' : 'hidden'">
                                            <li class="mb-1 last:mb-0">
                                                <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate {{ Request::is('financial') || Request::is('keuangan-perusahaan') ? 'text-violet-500!' : '' }}" href="{{ route('keuangan-perusahaan') }}">
                                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Keuangan Perusahaan (KP)</span>
                                                </a>
                                            </li>
                                            <li class="mb-1 last:mb-0">
                                                <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate {{ Request::is('buku-kas-kebun') ? 'text-violet-500!' : '' }}" href="{{ route('buku-kas-kebun') }}">
                                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Buku Kas Kebun (BKK)</span>
                                                </a>
                                            </li>
                                            <!-- <li class="mb-1 last:mb-0">
                                                <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate {{ Request::is('financial.cash-book') || Request::is('buku-kas-kebun') ? 'text-violet-500!' : '' }}" href="{{ route('buku-kas') }}">
                                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Buku Kas</span>
                                                </a>
                                            </li> -->
                                            <li class="mb-1 last:mb-0">
                                                <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate {{ Request::is('financial.debts') || Request::is('data-hutang') ? 'text-violet-500!' : '' }}" href="{{ route('data-hutang') }}">
                                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Data Hutang</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                
                                <!-- Data Karyawan -->
                                <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ Request::is('employees') || Request::is('data-karyawan') ? 'bg-linear-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                                    <a class="block text-gray-800 dark:text-gray-100 truncate transition {{ (Request::is('employees') || Request::is('data-karyawan')) ? '' : 'hover:text-gray-900 dark:hover:text-white' }}" href="{{ route('data-karyawan') }}">
                                        <div class="flex items-center">
                                            <svg class="shrink-0 fill-current {{ (Request::is('employees') || Request::is('data-karyawan')) ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                                <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 4v12c0 1.1-.9 2-2 2H6c-1.1 0-2-.9-2-2V8c0-1.1.9-2 2-2h1v-1c0-1.66 1.34-3 3-3s3 1.34 3 3v1h4c1.1 0 2 .9 2 2zm-8 9c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z"/>
                                            </svg>
                                            <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Data Karyawan</span>
                                        </div>
                                    </a>
                                </li>
                                
                                <!-- Akses User -->
                                <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ Request::is('akses-user') ? 'bg-linear-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                                    <a class="block text-gray-800 dark:text-gray-100 truncate transition {{ Request::is('akses-user') ? '' : 'hover:text-gray-900 dark:hover:text-white' }}" href="{{ route('akses-user') }}">
                                        <div class="flex items-center">
                                            <svg class="shrink-0 fill-current {{ Request::is('akses-user') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                            <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Akses User</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Settings group -->
                        <div>
                            <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                                <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6" aria-hidden="true">•••</span>
                                <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">System</span>
                            </h3>
                            <ul class="mt-3">
                                <!-- Settings -->
                                <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ Request::is('settings') ? 'bg-linear-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                                    <a class="block text-gray-800 dark:text-gray-100 truncate transition {{ Request::is('settings') ? '' : 'hover:text-gray-900 dark:hover:text-white' }}" href="{{ route('settings') }}">
                                        <div class="flex items-center">
                                            <svg class="shrink-0 fill-current {{ Request::is('settings') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                                <path d="M12 15l7-7H5z"/>
                                                <path d="M19.07 4.93l-1.41 1.41A7.97 7.97 0 0 0 20 12a8 8 0 1 1-8-8c1.98 0 3.76.78 5.07 2.07zM5.22 5.22A7.97 7.97 0 0 1 4 12a8 8 0 1 0 8 8c-1.98 0-3.76-.78-5.07-2.07l-1.41 1.41A9.95 9.95 0 0 1 2 12a10 10 0 0 1 3.22-7.22z"/>
                                            </svg>
                                            <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Pengaturan</span>
                                        </div>
                                    </a>
                                </li>
                                
                                <!-- Profile -->
                                <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 {{ Request::is('profile') ? 'bg-linear-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' : '' }}">
                                    <a class="block text-gray-800 dark:text-gray-100 truncate transition {{ Request::is('profile') ? '' : 'hover:text-gray-900 dark:hover:text-white' }}" href="{{ route('profile.show') }}">
                                        <div class="flex items-center">
                                            <svg class="shrink-0 fill-current {{ Request::is('profile') ? 'text-violet-500' : 'text-gray-400 dark:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                            <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Profil</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Expand / collapse button -->
                    <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto">
                        <div class="w-12 pl-4 pr-3 py-2">
                            <button class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 transition-colors" @click="sidebarExpanded = !sidebarExpanded">
                                <span class="sr-only">Expand / collapse sidebar</span>
                                <svg class="shrink-0 fill-current text-gray-400 dark:text-gray-500 sidebar-expanded:rotate-180" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M15 16a1 1 0 0 1-1-1V1a1 1 0 1 1 2 0v14a1 1 0 0 1-1 1ZM8.586 7H1a1 1 0 1 0 0 2h7.586l-2.793 2.793a1 1 0 1 0 1.414 1.414l4.5-4.5A.997.997 0 0 0 12 8.01M11.924 7.617a.997.997 0 0 0-.217-.324l-4.5-4.5a1 1 0 0 0-1.414 1.414L8.586 7M12 7.99a.996.996 0 0 0-.076-.373Z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content area -->
            <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
                <!-- Header -->
                <header class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700/60 z-30">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center justify-between h-16 -mb-px">
                            <!-- Header: Left side -->
                            <div class="flex">
                                <!-- Hamburger button -->
                                <button
                                    class="text-gray-500 hover:text-gray-600 lg:hidden mr-4"
                                    @click.stop="sidebarOpen = !sidebarOpen"
                                    aria-controls="sidebar"
                                    :aria-expanded="sidebarOpen"
                                >
                                    <span class="sr-only">Open sidebar</span>
                                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 5h18v2H3V5Zm0 6h18v2H3V11Zm0 6h18v2H3V17Z" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Header: Right side -->
                            <div class="flex items-center space-x-3">
                                <x-theme-toggle />
                                <x-dropdown-profile :align="'right'" />
                            </div>
                        </div>
                    </div>
                </header>

                <main class="grow">
                    @yield('content')
                </main>
            </div>
        </div>

        @livewireScripts
        @livewireScriptConfig
    </body>
</html>