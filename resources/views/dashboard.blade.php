@extends('layouts.app')
@section('title', 'Chauffeur Dashboard')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-6 fade-in-up">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-8 rounded-2xl shadow-xl">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-2">Chauffeur Dashboard</h1>
                <p class="text-blue-100 text-lg">Welcome back, {{ auth()->user()->name }}! Manage your vehicles and track your bookings.</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold">{{ now()->format('H:i') }}</div>
                <div class="text-blue-100">{{ now()->format('l, F j, Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Assigned Vehicles</p>
                    <p class="text-3xl font-bold text-blue-600" id="assigned-vehicles">{{ auth()->user()->vehicles()->count() ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Today's Bookings</p>
                    <p class="text-3xl font-bold text-green-600" id="today-bookings">0</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Hours Worked</p>
                    <p class="text-3xl font-bold text-yellow-600" id="hours-worked">8.5</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Earnings Today</p>
                    <p class="text-3xl font-bold text-purple-600" id="earnings-today">0 TND</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
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
                <a href="/map" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    <div>
                        <div class="font-medium text-gray-900">Live Map</div>
                        <div class="text-sm text-gray-500">Track vehicles</div>
                    </div>
                </a>
                
                <a href="/vehicles" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <div>
                        <div class="font-medium text-gray-900">My Vehicles</div>
                        <div class="text-sm text-gray-500">View assigned</div>
                    </div>
                </a>
                
                <a href="/reports" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                    <svg class="w-8 h-8 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <div>
                        <div class="font-medium text-gray-900">Reports</div>
                        <div class="text-sm text-gray-500">Trip analytics</div>
                    </div>
                </a>
                
                <a href="/settings" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <div>
                        <div class="font-medium text-gray-900">Settings</div>
                        <div class="text-sm text-gray-500">Account settings</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Recent Activity</h3>
            <div class="space-y-4" id="recent-activity">
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">Vehicle VH-001 started trip</div>
                        <div class="text-xs text-gray-500">2 minutes ago</div>
                    </div>
                </div>
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">New booking assigned</div>
                        <div class="text-xs text-gray-500">15 minutes ago</div>
                    </div>
                </div>
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">Trip completed</div>
                        <div class="text-xs text-gray-500">1 hour ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Vehicles -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-800">My Assigned Vehicles</h3>
            <a href="/vehicles" class="text-blue-600 hover:text-blue-800 font-medium">View All</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="assigned-vehicles-list">
            <!-- Vehicles will be loaded here -->
        </div>
    </div>
</div>

<script>
// Load assigned vehicles
function loadAssignedVehicles() {
    fetch('/api/v1/vehicles')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                const chauffeurVehicles = data.data.filter(v => v.chauffeur_id == {{ auth()->id() }});
                renderAssignedVehicles(chauffeurVehicles);
            } else {
                console.log('No vehicles data available');
                renderAssignedVehicles([]);
            }
        })
        .catch(error => {
            console.error('Error loading vehicles:', error);
        });
}

function renderAssignedVehicles(vehicles) {
    const container = document.getElementById('assigned-vehicles-list');
    
    if (vehicles.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No vehicles assigned</h3>
                <p class="text-gray-500">Contact your administrator to get vehicles assigned.</p>
            </div>
        `;
        return;
    }

    const vehiclesHtml = vehicles.map(vehicle => `
        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white text-xl">
                    ${getVehicleIcon(vehicle.vehicle_type)}
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-gray-900">${vehicle.vehicle_number}</h4>
                    <p class="text-sm text-gray-600">${vehicle.vehicle_type}</p>
                    <div class="flex items-center mt-1">
                        <div class="w-2 h-2 ${vehicle.is_active ? 'bg-green-500' : 'bg-red-500'} rounded-full mr-2"></div>
                        <span class="text-xs ${vehicle.is_active ? 'text-green-600' : 'text-red-600'}">${vehicle.is_active ? 'Active' : 'Inactive'}</span>
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
    loadAssignedVehicles();
    
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