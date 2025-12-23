<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ 
        darkMode: localStorage.getItem('darkMode') === 'true',
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        notifications: [],
        searchQuery: '',
        currentTime: new Date().toLocaleTimeString()
      }"
      x-init="
        $watch('darkMode', val => {
          localStorage.setItem('darkMode', val);
          document.documentElement.classList.toggle('dark', val);
        });
        $watch('sidebarCollapsed', val => localStorage.setItem('sidebarCollapsed', val));
        setInterval(() => currentTime = new Date().toLocaleTimeString(), 1000);
        if (darkMode) document.documentElement.classList.add('dark');
      "
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Car Tracking System')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js?v=<?php echo time(); ?>"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    
    <!-- Leaflet Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Pusher (disabled for now) -->
    <!-- <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script> -->
    
    <!-- Custom Styles -->
    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            @apply bg-gray-100 dark:bg-gray-800;
        }
        
        ::-webkit-scrollbar-thumb {
            @apply bg-gradient-to-b from-blue-500 to-purple-600 rounded-full;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            @apply from-blue-600 to-purple-700;
        }
        
        /* Glassmorphism Effects */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .dark .glass {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Animations */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes scaleIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .animate-slide-in-right {
            animation: slideInRight 0.3s ease-out;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        
        .animate-scale-in {
            animation: scaleIn 0.3s ease-out;
        }
        
        /* Sidebar Transitions */
        .sidebar-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Hover Effects */
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .dark .hover-lift:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }
        
        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        /* User Avatar Gradient */
        .avatar-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
        }
        
        /* Active State Gradient */
        .active-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            color: white;
        }
        
        /* Search Bar Focus */
        .search-focus:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            border-color: #3b82f6;
        }
        
        /* Dark Mode Toggle */
        .dark-toggle {
            position: relative;
            width: 60px;
            height: 30px;
            background: #e5e7eb;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .dark .dark-toggle {
            background: #374151;
        }
        
        .dark-toggle::before {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 26px;
            height: 26px;
            background: white;
            border-radius: 50%;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .dark .dark-toggle::before {
            transform: translateX(30px);
            background: #fbbf24;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar-mobile {
                transform: translateX(-100%);
            }
            
            .sidebar-mobile.open {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar-transition bg-white dark:bg-gray-800 shadow-lg z-30"
             :class="sidebarCollapsed ? 'w-16' : 'w-64'"
             x-show="!sidebarCollapsed || window.innerWidth > 768"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full">
            
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <div x-show="!sidebarCollapsed" class="flex items-center space-x-3">
                    <div class="w-8 h-8 avatar-gradient rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">CT</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold gradient-text">Car Tracking</h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Management System</p>
                    </div>
                </div>
                
                <!-- Collapse Toggle -->
                <button @click="sidebarCollapsed = !sidebarCollapsed"
                        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              :d="sidebarCollapsed ? 'M4 6h16M4 12h16M4 18h16' : 'M6 18L18 6M6 6l12 12'"></path>
                    </svg>
                </button>
            </div>
            
        <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 group"
                   :class="window.location.pathname === '/dashboard' ? 'active-gradient' : 'text-gray-700 dark:text-gray-300'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="font-medium">Dashboard</span>
                </a>
                
                <!-- Map -->
                <a href="{{ route('map') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 group"
                   :class="window.location.pathname === '/map' ? 'active-gradient' : 'text-gray-700 dark:text-gray-300'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="font-medium">Map</span>
                </a>
                
                <!-- Fleet Management -->
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.vehicles') }}" 
                           class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 group"
                           :class="window.location.pathname.includes('/admin/vehicles') ? 'active-gradient' : 'text-gray-700 dark:text-gray-300'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 2 0 11-4 0 2 2 0 014 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path>
                            </svg>
                            <span x-show="!sidebarCollapsed" class="font-medium">Fleet Management</span>
                        </a>
                    @elseif(auth()->user()->role === 'user' || auth()->user()->role === 'chauffeur')
                        <a href="{{ route('client.fleet') }}" 
                           class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 group"
                           :class="window.location.pathname === '/client/fleet' || window.location.pathname === '/client/vehicles' ? 'active-gradient' : 'text-gray-700 dark:text-gray-300'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path>
                            </svg>
                            <span x-show="!sidebarCollapsed" class="font-medium">Fleet</span>
                        </a>
                    @endif
                @endauth
                
                <!-- Reports -->
                <a href="{{ route('reports') }}" 
                   class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 group"
                   :class="window.location.pathname === '/reports' ? 'active-gradient' : 'text-gray-700 dark:text-gray-300'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="font-medium">Reports</span>
                </a>
                
                <!-- User-specific Navigation -->
                @auth
                    @if(auth()->user()->role === 'user')
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p x-show="!sidebarCollapsed" class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">User Actions</p>
                            
                            <a href="{{ route('client.rent.index') }}" 
                               class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 group mt-2"
                               :class="window.location.pathname.includes('/rent') ? 'active-gradient' : 'text-gray-700 dark:text-gray-300'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed" class="font-medium">Rent Car</span>
                            </a>
                            
                            <a href="{{ route('client.bookings.mine') }}" 
                               class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 group"
                               :class="window.location.pathname.includes('/user/bookings') ? 'active-gradient' : 'text-gray-700 dark:text-gray-300'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed" class="font-medium">My Bookings</span>
                            </a>
                            
                            <a href="{{ route('user.using-car') }}" 
                               class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 group"
                               :class="window.location.pathname.includes('/user/using-car') ? 'active-gradient' : 'text-gray-700 dark:text-gray-300'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed" class="font-medium">Using Car</span>
                            </a>
                        </div>
                    @elseif(auth()->user()->role === 'chauffeur')
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p x-show="!sidebarCollapsed" class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Chauffeur</p>
                            
                            <a href="{{ route('chauffeur.dashboard') }}" 
                               class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 group mt-2"
                               :class="window.location.pathname === '/chauffeur/dashboard' ? 'active-gradient' : 'text-gray-700 dark:text-gray-300'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed" class="font-medium">My Dashboard</span>
                            </a>
                        </div>
                    @elseif(auth()->user()->role === 'admin')
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p x-show="!sidebarCollapsed" class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Admin</p>
                            
                            <a href="{{ route('admin.dashboard') }}" 
                               class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 group mt-2"
                               :class="window.location.pathname === '/admin' ? 'active-gradient' : 'text-gray-700 dark:text-gray-300'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed" class="font-medium">Admin Panel</span>
                            </a>
                        </div>
                    @endif
                @endauth
                
                <!-- Profile -->
                @auth
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('profile.show') }}" 
                           class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 group"
                           :class="window.location.pathname.includes('/profile') ? 'active-gradient' : 'text-gray-700 dark:text-gray-300'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span x-show="!sidebarCollapsed" class="font-medium">Profile</span>
                        </a>
                    </div>
                @endauth
            </nav>
            
            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <!-- Dark Mode Toggle -->
                <div class="flex items-center justify-between mb-4">
                    <span x-show="!sidebarCollapsed" class="text-sm font-medium text-gray-700 dark:text-gray-300">Dark Mode</span>
                    <div class="dark-toggle" @click="darkMode = !darkMode"></div>
                </div>
                
                <!-- User Info -->
                @auth
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 avatar-gradient rounded-full flex items-center justify-center">
                            @if(auth()->user()->profile_image)
                                <img src="{{ Storage::url(auth()->user()->profile_image) }}" 
                                     alt="{{ auth()->user()->name }}" 
                                     class="w-8 h-8 rounded-full object-cover">
                            @else
                                <span class="text-white font-bold text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                            @endif
                        </div>
                        <div x-show="!sidebarCollapsed" class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between px-6 py-4">
                    <!-- Left Side -->
                    <div class="flex items-center space-x-4">
                        <!-- Mobile Menu Button -->
                        <button @click="sidebarCollapsed = !sidebarCollapsed" 
                                class="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                            <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        
                        <!-- Global Search -->
                        <div class="relative hidden md:block">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   x-model="searchQuery"
                                   placeholder="Search vehicles, drivers, locations..."
                                   class="search-focus block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none transition-colors duration-200">
                        </div>
                    </div>
                    
                    <!-- Right Side -->
                    <div class="flex items-center space-x-4">
                        <!-- Real-time Clock -->
                        <div class="hidden lg:flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span x-text="currentTime"></span>
                        </div>
                        
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6zM4 5h6V1H4v4zM15 5h5l-5-5v5z"></path>
                                </svg>
                                <div class="notification-badge" x-show="notifications.length > 0" x-text="notifications.length"></div>
                            </button>
                        </div>
                        
                        <!-- User Dropdown -->
                        @auth
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" 
                                        class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <div class="w-8 h-8 avatar-gradient rounded-full flex items-center justify-center">
                                        @if(auth()->user()->profile_image)
                                            <img src="{{ Storage::url(auth()->user()->profile_image) }}" 
                                                 alt="{{ auth()->user()->name }}" 
                                                 class="w-8 h-8 rounded-full object-cover">
                                        @else
                                            <span class="text-white font-bold text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                            @endif
                                    </div>
                                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div x-show="open" 
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-48 glass rounded-lg shadow-lg py-1 z-50">
                                    
                                    <a href="{{ route('profile.show') }}" 
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        My Profile
                                    </a>
                                    
                                    <a href="{{ route('profile.edit') }}" 
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit Profile
                                    </a>
                                    
                                    <div class="border-t border-gray-200 dark:border-gray-600"></div>
                                    
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" 
                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                                </div>
                            </div>
                        @else
                            <a href="/login" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-200 font-medium">
                                Login
                            </a>
                        @endauth
                    </div>
                </div>
            </header>

        <!-- Page Content -->
            <main class="flex-1 overflow-auto bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
                <div class="animate-fade-in">
                @yield('content')
            </div>
        </main>
    </div>
    </div>
    
    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarCollapsed && window.innerWidth <= 768" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden"
         @click="sidebarCollapsed = false"></div>
    
    <!-- Toast Notifications Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
    
    <!-- Global JavaScript -->
    <script>
        // Global booking functions - ensure they're always available
        window.openBookingModal = function(vehicleId, vehicleName, dailyRate) {
            const modal = document.getElementById('bookingModal');
            const vehicleIdInput = document.getElementById('modalVehicleId');
            const vehicleNameSpan = document.getElementById('modalVehicleName');
            
            if (modal && vehicleIdInput && vehicleNameSpan) {
                vehicleIdInput.value = vehicleId;
                vehicleNameSpan.textContent = vehicleName;
                modal.classList.remove('hidden');
            }
        };

        window.closeBookingModal = function() {
            const modal = document.getElementById('bookingModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        };

        window.switchView = function(view) {
            const listView = document.getElementById('listView');
            const mapView = document.getElementById('mapView');
            
            if (listView && mapView) {
                if (view === 'list') {
                    listView.classList.remove('hidden');
                    mapView.classList.add('hidden');
                } else {
                    listView.classList.add('hidden');
                    mapView.classList.remove('hidden');
                    
                    // Initialize map on first switch
                    if (!window.map) {
                        setTimeout(() => {
                            if (window.initMap) {
                                window.initMap();
                            }
                        }, 100);
                    } else {
                        window.map.invalidateSize();
                    }
                }
            }
        };

        window.focusVehicle = function(vehicleId, lat, lng) {
            if (window.map && lat && lng) {
                window.map.setView([lat, lng], 15);
                if (window.markers && window.markers[vehicleId]) {
                    window.markers[vehicleId].openPopup();
                }
            }
        };
        
        // Initialize Pusher for real-time updates (disabled for now)
        /*
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });
        
        // Subscribe to vehicle location updates
        const channel = pusher.subscribe('vehicle-locations');
        channel.bind('location-updated', function(data) {
            // Handle real-time location updates
            console.log('Location updated:', data);
        });
        */
        
        // Global search functionality
        function performSearch(query) {
            if (query.length < 2) return;
            
            // Implement search logic here
            console.log('Searching for:', query);
        }
        
        // Toast notification system
        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };
            
            toast.className = `${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg animate-slide-in-right`;
            toast.textContent = message;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }
        
        // Global error handler
        window.addEventListener('error', function(e) {
            showToast('An error occurred. Please try again.', 'error');
        });
        
        // Performance monitoring
        if ('performance' in window) {
            window.addEventListener('load', function() {
                const perfData = performance.getEntriesByType('navigation')[0];
                console.log('Page load time:', perfData.loadEventEnd - perfData.loadEventStart, 'ms');
            });
        }
    </script>
</body>
</html>