<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Page header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Akses User</h1>
    </div>

    <!-- Info Card -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm mb-8">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">User Management</h2>
        </div>
        <div class="p-5">
            <div class="text-center py-10">
                <div class="mx-auto w-16 h-16 rounded-full bg-violet-100 dark:bg-violet-500/30 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 fill-current text-violet-500 dark:text-violet-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2">User Access Management</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Manage user accounts, roles, and permissions for the palm oil plantation management system.
                </p>
                <div class="flex justify-center gap-3">
                    <a href="{{ route('profile') }}" class="px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors">
                        View Profile
                    </a>
                    <a href="{{ route('profile') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- User Information Card -->
    <div class="bg-white dark:bg-gray-800 rounded-sm border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Current User Information</h2>
        </div>
        <div class="p-5">
            <div class="flex items-start">
                <div class="mr-4">
                    <img class="w-16 h-16 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff" alt="{{ Auth::user()->name }}" />
                </div>
                <div class="flex-1">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-100 mb-1">{{ Auth::user()->name }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">{{ Auth::user()->email }}</p>
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-1">
                                <svg class="w-4 h-4 fill-current mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                                <span>Account verified</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4 fill-current mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                                    <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                                </svg>
                                <span>Member since {{ Auth::user()->created_at->format('M Y') }}</span>
                            </div>
                        </div>
                        <div>
                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4">
                                <h4 class="font-medium text-gray-800 dark:text-gray-100 mb-2">Account Status</h4>
                                <div class="flex items-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-800/30 dark:text-emerald-500">
                                        Active
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                    Your account is in good standing and has full access to all system features.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>