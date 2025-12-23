@extends('layouts.app')
@section('title', 'Fleet Overview')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-6 fade-in-up">
    <!-- Header -->
    <div class="modern-card p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-blue-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Fleet Overview</h1>
                <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}! View and manage your assigned vehicles.</p>
            </div>
            <div class="flex items-center space-x-4">
                <button id="refresh-fleet" class="btn btn-secondary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
                <a href="/map" class="btn btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    View Map
                </a>
            </div>
        </div>
    </div>

    <!-- Fleet Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="modern-card p-6 text-center">
            <div class="text-3xl font-bold text-blue-600" id="total-assigned">-</div>
            <div class="text-sm text-gray-600">My Vehicles</div>
        </div>
        <div class="modern-card p-6 text-center">
            <div class="text-3xl font-bold text-green-600" id="active-assigned">-</div>
            <div class="text-sm text-gray-600">Active Vehicles</div>
        </div>
        <div class="modern-card p-6 text-center">
            <div class="text-3xl font-bold text-yellow-600" id="total-bookings">-</div>
            <div class="text-sm text-gray-600">Total Bookings</div>
        </div>
        <div class="modern-card p-6 text-center">
            <div class="text-3xl font-bold text-purple-600" id="pending-bookings">-</div>
            <div class="text-sm text-gray-600">Pending Bookings</div>
        </div>
    </div>

    <!-- Vehicle List -->
    <div class="modern-card p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path>
                </svg>
                Assigned Vehicles
            </h2>
            <div class="flex items-center space-x-2">
                <select id="status-filter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all">All Vehicles</option>
                    <option value="active">Active Only</option>
                    <option value="inactive">Inactive Only</option>
                </select>
            </div>
        </div>
        
        <div id="vehicles-container">
            @if($vehicles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($vehicles as $vehicle)
                        <div class="vehicle-card border border-gray-200 rounded-lg p-6 hover:shadow-lg transition duration-200 bg-white" 
                             data-status="{{ $vehicle->is_active ? 'active' : 'inactive' }}">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    @if($vehicle->image_url)
                                    <img src="{{ $vehicle->image_url }}" 
                                         alt="{{ $vehicle->vehicle_number }}" 
                                             class="w-20 h-20 rounded-lg object-cover border-2 border-gray-200"
                                             onerror="this.src='/images/default-vehicle.svg'">
                                    @else
                                        <div class="w-20 h-20 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold">
                                            {{ $vehicle->vehicle_icon }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $vehicle->vehicle_number }}</h3>
                                    <div class="space-y-2 mb-4">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-700">{{ $vehicle->vehicle_type }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600">Driver: {{ $vehicle->driver_name ?? 'Not assigned' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600">Phone: {{ $vehicle->phone ?? 'Not provided' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600">Location: {{ $vehicle->latestLocation ? 'Available' : 'No location data' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm {{ $vehicle->is_active ? 'text-green-600 font-medium' : 'text-red-600 font-medium' }}">
                                                {{ $vehicle->is_active ? 'Available' : 'Unavailable' }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    @if($vehicle->is_active)
                                        <div class="mt-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Ready for Assignment
                                            </span>
                                        </div>
                                    @else
                                        <div class="mt-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Currently Unavailable
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No vehicles assigned</h3>
                    <p class="mt-1 text-sm text-gray-500">You don't have any vehicles assigned to you yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeChauffeurFleet();
});

function initializeChauffeurFleet() {
    updateFleetStatistics();
    bindEvents();
}

function bindEvents() {
    const refreshBtn = document.getElementById('refresh-fleet');
    const statusFilter = document.getElementById('status-filter');

    refreshBtn.addEventListener('click', function() {
        location.reload();
    });

    statusFilter.addEventListener('change', function() {
        filterVehicles(this.value);
    });
}

function filterVehicles(status) {
    const vehicleCards = document.querySelectorAll('.vehicle-card');
    
    vehicleCards.forEach(card => {
        const cardStatus = card.dataset.status;
        
        if (status === 'all' || cardStatus === status) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function updateFleetStatistics() {
    const vehicles = @json($vehicles);
    
    const totalAssigned = vehicles.length;
    const activeAssigned = vehicles.filter(v => v.is_active).length;
    const totalBookings = vehicles.reduce((sum, v) => sum + (v.bookings_count || 0), 0);
    const pendingBookings = vehicles.reduce((sum, v) => {
        return sum + (v.bookings ? v.bookings.filter(b => b.status === 'pending').length : 0);
    }, 0);

    document.getElementById('total-assigned').textContent = totalAssigned;
    document.getElementById('active-assigned').textContent = activeAssigned;
    document.getElementById('total-bookings').textContent = totalBookings;
    document.getElementById('pending-bookings').textContent = pendingBookings;
}

function getVehicleIcon(type) {
    const icons = {
        'Sedan': '🚙',
        'SUV': '🚗',
        'Truck': '🚛',
        'Van': '🚐',
        'Motorcycle': '🏍️',
        'Bus': '🚌',
        'Taxi': '🚕',
        'Hatchback': '🚘',
        'Pickup': '🛻',
        'Emergency': '🚑'
    };
    return icons[type] || '🚙';
}
</script>

<style>
.modern-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.btn {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-secondary:hover {
    background: #e5e7eb;
    transform: translateY(-1px);
}

.fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection