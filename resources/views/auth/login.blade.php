<x-authentication-layout>
    <div class="flex-1 mb-12 -mt-8">
        <div class="flex align-middle">
            <div class="flex items-center">
                <!-- Logo -->
                <a class="hidden md:block mt-4" href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/agropalma-logo.png') }}" width="50" height="50" alt="AgroPalma Logo" />
                </a>
                <!-- Company Name -->
                <span class="ml-3 text-2xl text-amber-600 dark:text-gray-100 font-bold mt-6 md:block hidden">{{ __('PT. Agro Palma Industri') }}</span>
            </div>
        </div>
    </div>
    <!-- Mobile illustration - only visible on mobile -->
    <div class="md:hidden mb-4 flex justify-center">
        <img src="{{ asset('images/login-illustration.png') }}" class="w-full object-contain rounded-2xl" alt="Login Illustration" />
    </div>
    <h1 class="text-3xl text-lime-800 dark:text-gray-100 font-bold mb-1">{{ __('Selamat Datang') }}</h1>
    <h4 class=" font-medium text-gray-400 dark:text-gray-100 mb-0">{{ __('Sistem Manajemen Digital') }}</h4>
    <h4 class=" font-medium text-gray-400 dark:text-gray-100 mb-6">{{ __('PT. Agro Palma Industri') }}</h4>
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif   
    <!-- Form -->
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="space-y-4">
            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" type="email" name="email" :value="old('email')" required autofocus />                
            </div>
            <div>
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" type="password" name="password" required autocomplete="current-password" />                
            </div>
        </div>
        <div class="flex items-center justify-between mt-6">
            {{-- @if (Route::has('password.request'))
                <div class="mr-1">
                    <a class="text-sm underline hover:no-underline" href="{{ route('password.request') }}">
                        {{ __('Forgot Password?') }}
                    </a>
                </div>
            @endif             --}}
            <x-button class="w-full" type="submit">
                {{ __('Masuk') }}
            </x-button>            
        </div>
    </form>
    <x-validation-errors class="mt-4" />   
    <!-- Footer -->
    <div class="pt-5 mt-6 border-t border-gray-100 dark:border-gray-700/60">
        {{-- <div class="text-sm">
            {{ __('Don\'t you have an account?') }} <a class="font-medium text-violet-500 hover:text-violet-600 dark:hover:text-violet-400" href="{{ route('register') }}">{{ __('Sign Up') }}</a>
        </div> --}}
        <!-- Warning -->
        <!-- <div class="mt-5">
            <div class="bg-yellow-500/20 text-yellow-700 px-3 py-2 rounded-lg">
                <svg class="inline w-3 h-3 shrink-0 fill-current" viewBox="0 0 12 12">
                    <path d="M10.28 1.28L3.989 7.575 1.695 5.28A1 1 0 00.28 6.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28 1.28z" />
                </svg>
                <span class="text-sm">
                    To support you during the pandemic super pro features are free until March 31st.
                </span>
            </div>
        </div> -->
    </div>
    
    <!-- Copyright -->
    <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
        &copy; {{ date('Y') }} PT. Agro Palma Industri. All rights reserved. Version 0.1
    </div>
</x-authentication-layout>
