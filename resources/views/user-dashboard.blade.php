@extends('layouts.app')
@section('title', 'User Dashboard')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-6 fade-in-up">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-700 text-white p-8 rounded-2xl shadow-xl">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-2">User Dashboard</h1>
                <p class="text-green-100 text-lg">Welcome back, {{ auth()->user()->name }}! Book vehicles and manage your trips.</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold">{{ now()->format('H:i') }}</div>
                <div class="text-green-100">{{ now()->format('l, F j, Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Bookings</p>
                    <p class="text-3xl font-bold text-blue-600" id="total-bookings">0</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Active Trips</p>
                    <p class="text-3xl font-bold text-green-600" id="active-trips">0</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Spent</p>
                    <p class="text-3xl font-bold text-purple-600" id="total-spent">0 TND</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Available Vehicles</p>
                    <p class="text-3xl font-bold text-orange-600" id="available-vehicles">0</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('client.rent.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <div class="font-medium text-gray-900">Book Vehicle</div>
                        <div class="text-sm text-gray-500">Rent a car</div>
                    </div>
                </a>
                
                <a href="{{ route('client.bookings.mine') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <div>
                        <div class="font-medium text-gray-900">My Bookings</div>
                        <div class="text-sm text-gray-500">View trips</div>
                    </div>
                </a>
                
                <a href="/user/using-car" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                    <svg class="w-8 h-8 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <div>
                        <div class="font-medium text-gray-900">Current Trip</div>
                        <div class="text-sm text-gray-500">Active journey</div>
                    </div>
                </a>
                
                <a href="/map" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    <div>
                        <div class="font-medium text-gray-900">Live Map</div>
                        <div class="text-sm text-gray-500">Track vehicles</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Recent Bookings</h3>
            <div class="space-y-4" id="recent-bookings">
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">Trip to Sousse</div>
                        <div class="text-xs text-gray-500">Today, 10:30 AM</div>
                    </div>
                    <div class="text-sm font-semibold text-green-600">Active</div>
                </div>
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">Airport Transfer</div>
                        <div class="text-xs text-gray-500">Yesterday, 2:15 PM</div>
                    </div>
                    <div class="text-sm font-semibold text-blue-600">Completed</div>
                </div>
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">City Tour</div>
                        <div class="text-xs text-gray-500">2 days ago</div>
                    </div>
                    <div class="text-sm font-semibold text-yellow-600">Pending</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Vehicles Preview -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-800">Available Vehicles</h3>
            <a href="{{ route('client.rent.index') }}" class="text-green-600 hover:text-green-800 font-medium">Book Now</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="available-vehicles-list">
            <!-- Vehicles will be loaded here -->
        </div>
    </div>

    <!-- Trip Tips -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Trip Tips</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">Book Early</h4>
                <p class="text-sm text-gray-600">Reserve your vehicle in advance for better rates and availability.</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">Check Status</h4>
                <p class="text-sm text-gray-600">Always verify your booking status before departure.</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">Contact Support</h4>
                <p class="text-sm text-gray-600">Need help? Contact our support team anytime.</p>
            </div>
        </div>
    </div>
</div>

<script>
// Load user statistics
function loadUserStats() {
    fetch('/api/v1/user-bookings-stats')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('total-bookings').textContent = data.stats.total_bookings || 0;
                document.getElementById('active-trips').textContent = data.stats.active_bookings || 0;
                document.getElementById('total-spent').textContent = (data.stats.total_spent || 0) + ' TND';
            }
        })
        .catch(error => {
            console.error('Error loading user stats:', error);
        });
}

// Load available vehicles
function loadAvailableVehicles() {
    fetch('/api/v1/vehicles')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const availableVehicles = data.vehicles.filter(v => v.is_active);
                document.getElementById('available-vehicles').textContent = availableVehicles.length;
                renderAvailableVehicles(availableVehicles.slice(0, 6)); // Show only first 6
            }
        })
        .catch(error => {
            console.error('Error loading vehicles:', error);
        });
}

function renderAvailableVehicles(vehicles) {
    const container = document.getElementById('available-vehicles-list');
    
    if (vehicles.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No vehicles available</h3>
                <p class="text-gray-500">Please check back later for available vehicles.</p>
            </div>
        `;
        return;
    }

    const vehiclesHtml = vehicles.map(vehicle => `
        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center text-white text-xl">
                    ${getVehicleIcon(vehicle.vehicle_type)}
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-gray-900">${vehicle.vehicle_number}</h4>
                    <p class="text-sm text-gray-600">${vehicle.vehicle_type}</p>
                    <div class="flex items-center justify-between mt-1">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                            <span class="text-xs text-green-600">Available</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">${vehicle.daily_rate || 'N/A'} TND/day</span>
                    </div>
                </div>
            </div>
        </div>
    `).join('');

    container.innerHTML = vehiclesHtml;
}

function getVehicleIcon(type) {
    const icons = {
        'Sedan': '🚙',
        'SUV': '🚗',
        'Van': '🚐',
        'Truck': '🚛',
        'Motorcycle': '🏍️',
        'Bus': '🚌',
        'Pickup': '🛻',
        'Emergency': '🚑',
        'Car': '🚙',
        'Luxury': '🏎️'
    };
    return icons[type] || '🚙';
}

// Load data on page load
document.addEventListener('DOMContentLoaded', function() {
    loadUserStats();
    loadAvailableVehicles();
    
    // Update time every minute
    setInterval(() => {
        const now = new Date();
        document.querySelector('.text-2xl').textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    }, 60000);
});
</script>

<style>
.fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection
