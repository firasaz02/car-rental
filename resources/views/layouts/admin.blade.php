<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">
</head>
<body class="font-sans antialiased bg-gray-100 role-admin">
    <div class="min-h-screen">
        <!-- Admin Navigation -->
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-gray-900">
                            Admin Panel
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('admin.dashboard') ? 'text-blue-600 font-semibold' : '' }}">
                            📊 Overview
                        </a>
                        <a href="{{ route('admin.map-dashboard') }}" 
                           class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('admin.map-dashboard') ? 'text-blue-600 font-semibold' : '' }}">
                            🗺️ Live Tracking
                        </a>
                        <a href="{{ route('admin.users') }}" 
                           class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('admin.users*') ? 'text-blue-600 font-semibold' : '' }}">
                            👥 Team
                        </a>
                        <a href="{{ route('admin.vehicles') }}" 
                           class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('admin.vehicles*') ? 'text-blue-600 font-semibold' : '' }}">
                            🚗 Fleet
                        </a>
                        <a href="{{ route('admin.bookings') }}" 
                           class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('admin.bookings*') ? 'text-blue-600 font-semibold' : '' }}">
                            📅 Reservations
                        </a>
                        
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-600">Welcome, {{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="btn-secondary">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <!-- Footer with Copyright -->
        <footer class="bg-gray-50 border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto py-6 px-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-sm text-gray-600 mb-4 md:mb-0">
                        © {{ date('Y') }} Car Tracking System. All rights reserved.
                    </div>
                    <div class="flex items-center space-x-6 text-sm text-gray-600">
                        <span>Powered by Laravel</span>
                        <span>•</span>
                        <span>Version 1.0</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
