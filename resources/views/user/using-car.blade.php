@extends('layouts.app')
@section('title', 'Using Car')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-6 fade-in-up">
    <!-- Header -->
    <div class="modern-card p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-green-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Using Car</h1>
                <p class="text-gray-600">Track your current vehicle usage and manage your active bookings</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('user.bookings') }}" class="btn btn-secondary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    My Bookings
                </a>
                <a href="{{ route('rent.index') }}" class="btn btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Rent Another
                </a>
            </div>
        </div>
    </div>

    <!-- Current Trip Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="modern-card p-6 text-center">
            <div class="text-3xl font-bold text-green-600" id="active-trips">-</div>
            <div class="text-sm text-gray-600">Active Trips</div>
        </div>
        <div class="modern-card p-6 text-center">
            <div class="text-3xl font-bold text-blue-600" id="total-distance">-</div>
            <div class="text-sm text-gray-600">Total Distance</div>
        </div>
        <div class="modern-card p-6 text-center">
            <div class="text-3xl font-bold text-yellow-600" id="trip-duration">-</div>
            <div class="text-sm text-gray-600">Trip Duration</div>
        </div>
        <div class="modern-card p-6 text-center">
            <div class="text-3xl font-bold text-purple-600" id="fuel-level">-</div>
            <div class="text-sm text-gray-600">Fuel Level</div>
        </div>
    </div>

    <!-- Active Vehicle Information -->
    <div id="active-vehicle-section">
        @if($activeBookings->count() > 0)
            @foreach($activeBookings as $booking)
                <div class="modern-card p-6 mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path>
                            </svg>
                            Currently Using: {{ $booking->vehicle->vehicle_number }}
                        </h2>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                {{ ucfirst($booking->status) }}
                            </span>
                            <button onclick="refreshVehicleData({{ $booking->vehicle->id }})" class="btn btn-secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Refresh
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Vehicle Details -->
                        <div class="lg:col-span-2">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    @if($booking->vehicle->image_url)
                                        <img src="{{ $booking->vehicle->image_url }}" 
                                             alt="{{ $booking->vehicle->vehicle_number }}" 
                                             class="w-24 h-24 rounded-lg object-cover border-2 border-gray-200"
                                             onerror="this.src='/images/default-vehicle.svg'">
                                    @else
                                        <div class="w-24 h-24 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-3xl font-bold">
                                            {{ $booking->vehicle->vehicle_icon }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $booking->vehicle->vehicle_number }}</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                                <span class="text-sm font-medium text-gray-700">{{ $booking->vehicle->vehicle_type }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <span class="text-sm text-gray-600">Driver: {{ $booking->vehicle->driver_name ?? 'Not assigned' }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                                <span class="text-sm text-gray-600">Phone: {{ $booking->vehicle->phone ?? 'Not provided' }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-sm font-medium text-gray-700">Start: {{ $booking->start_date->format('M d, Y') }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-sm font-medium text-gray-700">End: {{ $booking->end_date->format('M d, Y') }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-sm text-gray-600">Duration: {{ $booking->start_date->diffInDays($booking->end_date) + 1 }} days</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Trip Actions -->
                        <div class="space-y-4">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h4 class="font-semibold text-green-800 mb-2">Trip Status</h4>
                                <p class="text-sm text-green-700">You are currently using this vehicle</p>
                            </div>
                            
                            <div class="space-y-2">
                                @if($booking->end_date->isToday())
                                    <button onclick="completeTrip({{ $booking->id }})" 
                                            class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                                        Complete Trip
                                    </button>
                                @endif
                                
                                <button onclick="reportIssue({{ $booking->id }})" 
                                        class="w-full px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition duration-200">
                                    Report Issue
                                </button>
                                
                                <a href="/map" 
                                   class="w-full px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 text-center block">
                                    View on Map
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Location and Status -->
                <div class="modern-card p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Vehicle Location & Status
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-800 mb-2">Current Location</h4>
                            <div id="vehicle-location-{{ $booking->vehicle->id }}">
                                @if($booking->vehicle->latestLocation)
                                    <p class="text-sm text-blue-700">
                                        {{ $booking->vehicle->latestLocation->latitude }}, {{ $booking->vehicle->latestLocation->longitude }}
                                    </p>
                                    <p class="text-xs text-blue-600 mt-1">
                                        Last updated: {{ $booking->vehicle->latestLocation->recorded_at->diffForHumans() }}
                                    </p>
                                @else
                                    <p class="text-sm text-blue-700">Location data not available</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <h4 class="font-semibold text-green-800 mb-2">Speed</h4>
                            <div id="vehicle-speed-{{ $booking->vehicle->id }}">
                                @if($booking->vehicle->latestLocation)
                                    <p class="text-sm text-green-700">{{ $booking->vehicle->latestLocation->speed ?? 0 }} km/h</p>
                                @else
                                    <p class="text-sm text-green-700">Speed data not available</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h4 class="font-semibold text-yellow-800 mb-2">Fuel Level</h4>
                            <div id="vehicle-fuel-{{ $booking->vehicle->id }}">
                                <p class="text-sm text-yellow-700">85% (Estimated)</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="modern-card p-6">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No active trips</h3>
                    <p class="mt-1 text-sm text-gray-500">You don't have any active vehicle bookings at the moment.</p>
                    <div class="mt-6">
                        <a href="{{ route('rent.index') }}" class="btn btn-primary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Rent a Vehicle
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeUsingCarPage();
});

function initializeUsingCarPage() {
    updateTripStats();
    bindEvents();
    startLocationUpdates();
}

function bindEvents() {
    // Add any event listeners here
}

function updateTripStats() {
    const activeBookings = @json($activeBookings);
    
    const activeTrips = activeBookings.length;
    const totalDistance = activeBookings.reduce((sum, booking) => sum + (booking.vehicle.latest_location?.distance || 0), 0);
    const tripDuration = activeBookings.reduce((sum, booking) => {
        const start = new Date(booking.start_date);
        const now = new Date();
        return sum + Math.floor((now - start) / (1000 * 60 * 60)); // hours
    }, 0);
    const fuelLevel = 85; // This would come from vehicle data

    document.getElementById('active-trips').textContent = activeTrips;
    document.getElementById('total-distance').textContent = totalDistance.toFixed(1) + ' km';
    document.getElementById('trip-duration').textContent = tripDuration + ' hrs';
    document.getElementById('fuel-level').textContent = fuelLevel + '%';
}

function startLocationUpdates() {
    // Update location data every 30 seconds
    setInterval(() => {
        const activeBookings = @json($activeBookings);
        activeBookings.forEach(booking => {
            updateVehicleLocation(booking.vehicle.id);
        });
    }, 30000);
}

function updateVehicleLocation(vehicleId) {
    fetch(`/api/v1/vehicles/${vehicleId}/location`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.location) {
                const locationElement = document.getElementById(`vehicle-location-${vehicleId}`);
                const speedElement = document.getElementById(`vehicle-speed-${vehicleId}`);
                
                if (locationElement) {
                    locationElement.innerHTML = `
                        <p class="text-sm text-blue-700">
                            ${data.location.latitude}, ${data.location.longitude}
                        </p>
                        <p class="text-xs text-blue-600 mt-1">
                            Last updated: ${new Date(data.location.recorded_at).toLocaleTimeString()}
                        </p>
                    `;
                }
                
                if (speedElement) {
                    speedElement.innerHTML = `
                        <p class="text-sm text-green-700">${data.location.speed || 0} km/h</p>
                    `;
                }
            }
        })
        .catch(error => {
            console.error('Error updating vehicle location:', error);
        });
}

function refreshVehicleData(vehicleId) {
    updateVehicleLocation(vehicleId);
    showMessage('Vehicle data refreshed!', 'success');
}

function completeTrip(bookingId) {
    if (confirm('Are you ready to complete your trip?')) {
        fetch(`/user/bookings/${bookingId}/complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('Trip completed successfully!', 'success');
                setTimeout(() => location.reload(), 2000);
            } else {
                showMessage('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error completing trip:', error);
            showMessage('An error occurred', 'error');
        });
    }
}

function reportIssue(bookingId) {
    const issue = prompt('Please describe the issue:');
    if (issue && issue.trim()) {
        fetch(`/user/bookings/${bookingId}/report-issue`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ issue: issue.trim() })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('Issue reported successfully!', 'success');
            } else {
                showMessage('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error reporting issue:', error);
            showMessage('An error occurred', 'error');
        });
    }
}

function showMessage(message, type) {
    const messageEl = document.createElement('div');
    messageEl.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white font-semibold z-50 transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    messageEl.textContent = message;

    document.body.appendChild(messageEl);

    setTimeout(() => {
        messageEl.style.transform = 'translateX(100%)';
        setTimeout(() => messageEl.remove(), 300);
    }, 3000);
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
