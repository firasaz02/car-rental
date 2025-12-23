{{-- resources/views/rent/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Rent a Vehicle')

@section('content')
<div class="animate-fade-in">
    {{-- Header with View Toggle --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Rent a Vehicle</h1>
            <p class="text-gray-600 dark:text-gray-400">Choose your perfect vehicle from our fleet</p>
        </div>
        
        {{-- View Toggle Menu --}}
        <div x-data="{ view: 'list' }" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-1 inline-flex">
            <button @click="view = 'list'; switchView('list')" 
                    :class="view === 'list' ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                    class="px-4 py-2 rounded-md transition-all duration-200 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                <span class="font-medium">List View</span>
            </button>
            <button @click="view = 'map'; switchView('map')" 
                    :class="view === 'map' ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                    class="px-4 py-2 rounded-md transition-all duration-200 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
                <span class="font-medium">Map View</span>
            </button>
        </div>
    </div>

    {{-- Filters Panel --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filters</h3>
        <form method="GET" action="{{ route('client.rent.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Date Range --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                <input type="date" name="start_date" 
                       value="{{ request('start_date', today()->format('Y-m-d')) }}"
                       min="{{ today()->format('Y-m-d') }}"
                       class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:text-white"
                       required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                <input type="date" name="end_date" 
                       value="{{ request('end_date', today()->addDays(1)->format('Y-m-d')) }}"
                       min="{{ today()->addDay()->format('Y-m-d') }}"
                       class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:text-white"
                       required>
            </div>

            {{-- Vehicle Type Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vehicle Type</label>
                <select name="type" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:text-white">
                    <option value="">All Types</option>
                    <option value="Sedan" {{ request('type') === 'Sedan' ? 'selected' : '' }}>Sedan</option>
                    <option value="SUV" {{ request('type') === 'SUV' ? 'selected' : '' }}>SUV</option>
                    <option value="Van" {{ request('type') === 'Van' ? 'selected' : '' }}>Van</option>
                    <option value="Truck" {{ request('type') === 'Truck' ? 'selected' : '' }}>Truck</option>
                    <option value="Motorcycle" {{ request('type') === 'Motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                    <option value="Bus" {{ request('type') === 'Bus' ? 'selected' : '' }}>Bus</option>
                    <option value="Pickup" {{ request('type') === 'Pickup' ? 'selected' : '' }}>Pickup</option>
                    <option value="Emergency" {{ request('type') === 'Emergency' ? 'selected' : '' }}>Emergency</option>
                    <option value="Luxury" {{ request('type') === 'Luxury' ? 'selected' : '' }}>Luxury</option>
                </select>
            </div>

            {{-- Search Button --}}
            <div class="flex items-end">
                <button type="submit" class="w-full px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 font-medium">
                    Search Vehicles
                </button>
            </div>
        </form>
    </div>

    {{-- LIST VIEW --}}
    <div id="listView" class="view-container">
        @if($vehicles->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No vehicles available</h3>
                <p class="text-gray-600 dark:text-gray-400">Try adjusting your filters or dates</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($vehicles as $vehicle)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 animate-scale-in">
                        {{-- Vehicle Image --}}
                        @if($vehicle->image_url)
                            <div class="h-48 w-full overflow-hidden bg-gray-200">
                                <img src="{{ $vehicle->image_url }}" 
                                     alt="{{ $vehicle->vehicle_number }}" 
                                     class="w-full h-full object-cover"
                                     onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center hidden">
                                    <div class="text-white text-6xl font-bold opacity-75">
                                        {{ $vehicle->vehicle_icon }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                <div class="text-white text-6xl font-bold opacity-75">
                                    {{ $vehicle->vehicle_icon }}
                                </div>
                            </div>
                        @endif

                        <div class="p-6">
                            {{-- Vehicle Info --}}
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1">{{ $vehicle->vehicle_number }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $vehicle->vehicle_type }}</p>
                                    @if($vehicle->make && $vehicle->model)
                                        <p class="text-sm text-gray-500 dark:text-gray-500">{{ $vehicle->make }} {{ $vehicle->model }}</p>
                                    @endif
                                </div>
                                @if($vehicle->is_available)
                                    <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 text-xs font-semibold rounded-full">
                                        Available
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 text-xs font-semibold rounded-full">
                                        Booked
                                    </span>
                                @endif
                            </div>

                            {{-- Vehicle Details --}}
                            <div class="mb-4 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                @if($vehicle->chauffeur)
                                    <div class="flex items-center space-x-2 p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <div>
                                            <span class="font-medium text-blue-800 dark:text-blue-300">Chauffeur:</span>
                                            <span class="text-blue-700 dark:text-blue-200">{{ $vehicle->chauffeur->name }}</span>
                                        </div>
                                    </div>
                                    @if($vehicle->chauffeur->phone)
                                        <p class="flex items-center space-x-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            <span>{{ $vehicle->chauffeur->phone }}</span>
                                        </p>
                                    @endif
                                @elseif($vehicle->driver_name)
                                    <p class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span>Driver: {{ $vehicle->driver_name }}</span>
                                    </p>
                                @else
                                    <div class="flex items-center space-x-2 p-2 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                        <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        <span class="text-yellow-700 dark:text-yellow-200">No chauffeur assigned</span>
                                    </div>
                                @endif
                                @if($vehicle->phone)
                                    <p class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        <span>{{ $vehicle->phone }}</span>
                                    </p>
                                @endif
                                @if($vehicle->daily_rate)
                                    <p class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        </svg>
                                        <span>{{ $vehicle->daily_rate }} TND/day</span>
                                    </p>
                                @endif
                                @if($vehicle->capacity)
                                    <p class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span>{{ $vehicle->capacity }} seats</span>
                                    </p>
                                @endif
                            </div>

                            {{-- Action Button --}}
                            @if($vehicle->is_available)
                                <button onclick="openBookingModal({{ $vehicle->id }}, '{{ $vehicle->vehicle_number }}', {{ $vehicle->daily_rate ?? 0 }}, @if($vehicle->chauffeur){ name: '{{ $vehicle->chauffeur->name }}', phone: '{{ $vehicle->chauffeur->phone }}' }@else null @endif)" 
                                        class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 font-medium">
                                    Book Now
                                </button>
                            @else
                                <button disabled class="w-full px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-lg cursor-not-allowed">
                                    Not Available
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- MAP VIEW --}}
    <div id="mapView" class="view-container hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden" style="height: 600px;">
            {{-- Sidebar with vehicle list --}}
            <div class="flex h-full">
                <div class="w-80 overflow-y-auto border-r border-gray-200 dark:border-gray-700 p-4 space-y-3">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Available Vehicles</h3>
                    @foreach($vehicles as $vehicle)
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:shadow-md transition-shadow cursor-pointer"
                             onclick="focusVehicle({{ $vehicle->id }}, {{ $vehicle->latestLocation ? $vehicle->latestLocation->latitude : 36.8065 }}, {{ $vehicle->latestLocation ? $vehicle->latestLocation->longitude : 10.1815 }})">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold text-gray-900 dark:text-white">{{ $vehicle->vehicle_number }}</h4>
                                @if($vehicle->is_available)
                                    <span class="px-2 py-0.5 bg-green-100 text-green-800 text-xs rounded-full">Available</span>
                                @else
                                    <span class="px-2 py-0.5 bg-red-100 text-red-800 text-xs rounded-full">Booked</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $vehicle->vehicle_type }}</p>
                            @if($vehicle->daily_rate)
                                <p class="text-sm text-gray-500 dark:text-gray-500">{{ $vehicle->daily_rate }} TND/day</p>
                            @endif
                            @if($vehicle->is_available)
                                <button onclick="event.stopPropagation(); openBookingModal({{ $vehicle->id }}, '{{ $vehicle->vehicle_number }}', {{ $vehicle->daily_rate ?? 0 }}, @if($vehicle->chauffeur){ name: '{{ $vehicle->chauffeur->name }}', phone: '{{ $vehicle->chauffeur->phone }}' }@else null @endif)" 
                                        class="mt-3 w-full px-3 py-1.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                                    Book Now
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Map Container --}}
                <div class="flex-1" id="rentMap"></div>
            </div>
        </div>
    </div>
</div>

{{-- Booking Modal --}}
<div id="bookingModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl w-full max-w-md mx-4 animate-scale-in">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Confirm Booking</h3>
                <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('client.rent.store') }}" method="POST">
                @csrf
                <input type="hidden" name="vehicle_id" id="modalVehicleId">

                <div class="mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Vehicle:</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white" id="modalVehicleName"></p>
                    <div id="modalChauffeurInfo" class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <!-- Chauffeur info will be populated here -->
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                    <input type="date" name="start_date" 
                           value="{{ request('start_date', today()->format('Y-m-d')) }}"
                           min="{{ today()->format('Y-m-d') }}"
                           class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:text-white"
                           required>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                    <input type="date" name="end_date" 
                           value="{{ request('end_date', today()->addDays(1)->format('Y-m-d')) }}"
                           min="{{ today()->addDay()->format('Y-m-d') }}"
                           class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:text-white"
                           required>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeBookingModal()" 
                            class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-medium">
                        Confirm Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Global booking functions -->
<script>
// Make functions globally accessible
window.openBookingModal = function(vehicleId, vehicleName, dailyRate, chauffeurInfo = null) {
    document.getElementById('modalVehicleId').value = vehicleId;
    document.getElementById('modalVehicleName').textContent = vehicleName;
    
    // Populate chauffeur information
    const chauffeurInfoDiv = document.getElementById('modalChauffeurInfo');
    if (chauffeurInfo && chauffeurInfo.name) {
        chauffeurInfoDiv.innerHTML = `
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <div>
                    <span class="font-medium text-blue-800 dark:text-blue-300">Chauffeur:</span>
                    <span class="text-blue-700 dark:text-blue-200">${chauffeurInfo.name}</span>
                    ${chauffeurInfo.phone ? `<div class="text-sm text-blue-600 dark:text-blue-400">📞 ${chauffeurInfo.phone}</div>` : ''}
                </div>
            </div>
        `;
        chauffeurInfoDiv.classList.remove('hidden');
    } else {
        chauffeurInfoDiv.innerHTML = `
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <span class="text-yellow-700 dark:text-yellow-200">No chauffeur assigned</span>
            </div>
        `;
        chauffeurInfoDiv.classList.remove('hidden');
    }
    
    document.getElementById('bookingModal').classList.remove('hidden');
};

window.closeBookingModal = function() {
    document.getElementById('bookingModal').classList.add('hidden');
};

window.switchView = function(view) {
    const listView = document.getElementById('listView');
    const mapView = document.getElementById('mapView');
    
    if (view === 'list') {
        listView.classList.remove('hidden');
        mapView.classList.add('hidden');
    } else {
        listView.classList.add('hidden');
        mapView.classList.remove('hidden');
        
        // Initialize map on first switch
        if (!window.map) {
            setTimeout(() => {
                window.initMap();
            }, 100);
        } else {
            window.map.invalidateSize();
        }
    }
};

window.focusVehicle = function(vehicleId, lat, lng) {
    if (window.map && lat && lng) {
        window.map.setView([lat, lng], 15);
        if (window.markers[vehicleId]) {
            window.markers[vehicleId].openPopup();
        }
    }
};
</script>

<script>
let map;
let markers = {};

// Initialize Leaflet Map
function initMap() {
    map = L.map('rentMap').setView([36.8065, 10.1815], 12);
    
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Add markers for all vehicles
    @foreach($vehicles as $vehicle)
        @if($vehicle->latestLocation)
            @if($vehicle->image_url)
                const icon{{ $vehicle->id }} = L.icon({
                    iconUrl: '{{ $vehicle->image_url }}',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20],
                    popupAnchor: [0, -20],
                    className: 'vehicle-marker-icon'
                });
            @else
                const icon{{ $vehicle->id }} = L.divIcon({
                    className: 'vehicle-marker-default',
                    html: '<div style="font-size: 2rem;">{{ $vehicle->vehicle_icon }}</div>',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20],
                    popupAnchor: [0, -20]
                });
            @endif
            
            const marker{{ $vehicle->id }} = L.marker([{{ $vehicle->latestLocation->latitude }}, {{ $vehicle->latestLocation->longitude }}], {icon: icon{{ $vehicle->id }}})
                .addTo(map)
                .bindPopup(`
                    <div class="p-2">
                        @if($vehicle->image_url)
                            <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->vehicle_number }}" class="w-full h-32 object-cover rounded mb-2" onerror="this.style.display='none';">
                        @endif
                        <h4 class="font-bold">{{ $vehicle->vehicle_number }}</h4>
                        <p class="text-sm">{{ $vehicle->vehicle_type }}</p>
                        <p class="text-sm">Driver: {{ $vehicle->driver_name }}</p>
                        @if($vehicle->daily_rate)
                            <p class="text-sm">{{ $vehicle->daily_rate }} TND/day</p>
                        @endif
                        @if($vehicle->is_available)
                            <button onclick="openBookingModal({{ $vehicle->id }}, '{{ $vehicle->vehicle_number }}', {{ $vehicle->daily_rate ?? 0 }}, @if($vehicle->chauffeur){ name: '{{ $vehicle->chauffeur->name }}', phone: '{{ $vehicle->chauffeur->phone }}' }@else null @endif)" 
                                    class="mt-2 px-3 py-1 bg-blue-600 text-white text-sm rounded">
                                Book Now
                            </button>
                        @else
                            <p class="text-xs text-red-600 mt-2">Not Available</p>
                        @endif
                    </div>
                `);
            
            markers[{{ $vehicle->id }}] = marker{{ $vehicle->id }};
        @endif
    @endforeach
}

// Make map and markers globally accessible
window.map = map;
window.markers = markers;
window.initMap = initMap;
</script>

<script>
// Close modal on outside click
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('bookingModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeBookingModal();
            }
        });
    }
});
</script>
@endpush

<style>
.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

/* Leaflet marker icon styles */
.vehicle-marker-icon {
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

.vehicle-marker-default {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
}

.animate-scale-in {
    animation: scaleIn 0.3s ease-out;
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
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>
@endsection