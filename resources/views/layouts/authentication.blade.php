<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles        

        <script>
            if (localStorage.getItem('dark-mode') === 'false' || !('dark-mode' in localStorage)) {
                document.querySelector('html').classList.remove('dark');
                document.querySelector('html').style.colorScheme = 'light';
            } else {
                document.querySelector('html').classList.add('dark');
                document.querySelector('html').style.colorScheme = 'dark';
            }
        </script>
    </head>
    <body class="font-inter antialiased bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400">

        <main class=" bg-amber-50 dark:bg-gray-900">

            <div class="relative flex">

                <!-- Content -->
                <div class="w-full md:w-1/2">

                    <div class="min-h-[100dvh] h-full flex flex-col after:flex-1">

                        <!-- Header -->
                        <div class="flex-1 mt-4 md:mt-0">
                            <div class="flex align-middle h-16 px-4 sm:px-6 lg:px-8">
                                <div class="flex items-center">
                                    <!-- Logo -->
                                    <a class="block mt-4 ml-3 md:hidden" href="{{ route('dashboard') }}">
                                        <img src="{{ asset('images/agropalma-logo.png') }}" width="50" height="50" alt="AgroPalma Logo" />
                                    </a>
                                    <!-- Company Name -->
                                    <span class="ml-3 text-2xl text-amber-600 dark:text-gray-100 font-bold mt-6 md:hidden">{{ __('PT. Agro Palma Industri') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="max-w-sm mx-auto w-full px-4 py-8">
                            {{ $slot }}
                        </div>

                    </div>

                </div>

                <!-- Image -->
                <div class="hidden md:block absolute top-0 bottom-0 right-0 md:w-1/2" aria-hidden="true">
                    <img class="object-cover object-center w-full h-full" src="{{ asset('images/auth-image.png') }}" width="760" height="1024" alt="Authentication image" />
                </div>

            </div>

        </main> 

        @livewireScriptConfig
    </body>
</html>
