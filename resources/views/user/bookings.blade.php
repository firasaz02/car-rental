@extends('layouts.app')
@section('title', 'My Bookings')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-6 fade-in-up">
    <!-- Header -->
    <div class="modern-card p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-green-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">My Bookings</h1>
                <p class="text-gray-600">Manage your vehicle bookings and track their status</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('client.rent.index') }}" class="btn btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Rent New Vehicle
                </a>
                <a href="{{ route('user.using-car') }}" class="btn btn-secondary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Using Car
                </a>
            </div>
        </div>
    </div>

    <!-- Booking Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="modern-card p-6 text-center">
            <div class="text-3xl font-bold text-blue-600" id="total-bookings">-</div>
            <div class="text-sm text-gray-600">Total Bookings</div>
        </div>
        <div class="modern-card p-6 text-center">
            <div class="text-3xl font-bold text-green-600" id="active-bookings">-</div>
            <div class="text-sm text-gray-600">Active Bookings</div>
        </div>
        <div class="modern-card p-6 text-center">
            <div class="text-3xl font-bold text-yellow-600" id="pending-bookings">-</div>
            <div class="text-sm text-gray-600">Pending Bookings</div>
        </div>
        <div class="modern-card p-6 text-center">
            <div class="text-3xl font-bold text-purple-600" id="completed-bookings">-</div>
            <div class="text-sm text-gray-600">Completed Bookings</div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="modern-card p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 md:space-x-4">
            <div class="flex flex-col md:flex-row md:items-center space-y-2 md:space-y-0 md:space-x-4">
                <div>
                    <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Status</label>
                    <select id="status-filter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">All Bookings</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div>
                    <label for="date-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Date</label>
                    <select id="date-filter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">All Dates</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="current">Current</option>
                        <option value="past">Past</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button id="refresh-bookings" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Bookings List -->
    <div class="modern-card p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Your Bookings
            </h2>
        </div>

        <div id="bookings-container">
            @if($bookings->count() > 0)
                <div class="space-y-6">
                    @foreach($bookings as $booking)
                        <div class="booking-card border border-gray-200 rounded-lg p-6 hover:shadow-lg transition duration-200 bg-white" 
                             data-status="{{ $booking->status }}" 
                             data-date-range="{{ $booking->start_date->format('Y-m-d') }}-{{ $booking->end_date->format('Y-m-d') }}">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        @if($booking->vehicle->image_url)
                                            <img src="{{ $booking->vehicle->image_url }}" 
                                                 alt="{{ $booking->vehicle->vehicle_number }}" 
                                                 class="w-20 h-20 rounded-lg object-cover border-2 border-gray-200"
                                                 onerror="this.src='/images/default-vehicle.svg'">
                                        @else
                                            <div class="w-20 h-20 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold">
                                                {{ $booking->vehicle->vehicle_icon }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h3 class="text-xl font-bold text-gray-900">{{ $booking->vehicle->vehicle_number }}</h3>
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $booking->status_badge_class }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
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
                                                    <span class="text-sm text-blue-600 font-medium">Chauffeur: {{ $booking->chauffeur->name ?? 'Not assigned' }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                    </svg>
                                                    <span class="text-sm text-gray-600">Phone: {{ $booking->chauffeur->phone ?? $booking->vehicle->phone ?? 'Not provided' }}</span>
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
                                        
                                        @if($booking->status === 'pending')
                                            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                    </svg>
                                                    <span class="text-yellow-800 font-medium">Waiting for confirmation from admin</span>
                                                </div>
                                            </div>
                                        @elseif($booking->status === 'confirmed')
                                            <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <span class="text-green-800 font-medium">Booking confirmed! You can pick up the vehicle on {{ $booking->start_date->format('M d, Y') }}</span>
                                                </div>
                                            </div>
                                        @elseif($booking->status === 'in_progress')
                                            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                    <span class="text-blue-800 font-medium">Currently using this vehicle</span>
                                                </div>
                                            </div>
                                        @elseif($booking->status === 'completed')
                                            <div class="mt-4 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <span class="text-gray-800 font-medium">Booking completed successfully</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex flex-col space-y-2">
                                    @if($booking->status === 'pending')
                                        <button onclick="cancelBooking({{ $booking->id }})" 
                                                class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200">
                                            Cancel Booking
                                        </button>
                                    @endif
                                    
                                    @if($booking->status === 'confirmed' && $booking->start_date->isToday())
                                        <button onclick="startBooking({{ $booking->id }})" 
                                                class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200">
                                            Start Trip
                                        </button>
                                    @endif
                                    
                                    @if($booking->status === 'in_progress' && $booking->end_date->isToday())
                                        <button onclick="completeBooking({{ $booking->id }})" 
                                                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                                            Complete Trip
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('user.using-car') }}" 
                                       class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 text-center">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings found</h3>
                    <p class="mt-1 text-sm text-gray-500">You haven't made any vehicle bookings yet.</p>
                    <div class="mt-6">
                        <a href="{{ route('client.rent.index') }}" class="btn btn-primary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Rent Your First Vehicle
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeBookingsPage();
});

function initializeBookingsPage() {
    try {
        updateBookingStats();
        bindEvents();
    } catch (error) {
        console.error('Error initializing bookings page:', error);
        // Fallback: at least try to bind events even if stats fail
        try {
            bindEvents();
        } catch (bindError) {
            console.error('Error binding events:', bindError);
        }
    }
}

function bindEvents() {
    const statusFilter = document.getElementById('status-filter');
    const dateFilter = document.getElementById('date-filter');
    const refreshBtn = document.getElementById('refresh-bookings');

    statusFilter.addEventListener('change', function() {
        filterBookings();
    });

    dateFilter.addEventListener('change', function() {
        filterBookings();
    });

    refreshBtn.addEventListener('click', function() {
        location.reload();
    });
}

function filterBookings() {
    const statusFilter = document.getElementById('status-filter').value;
    const dateFilter = document.getElementById('date-filter').value;
    const bookingCards = document.querySelectorAll('.booking-card');
    
    bookingCards.forEach(card => {
        const cardStatus = card.dataset.status;
        const cardDateRange = card.dataset.dateRange;
        const startDate = new Date(cardDateRange.split('-')[0]);
        const endDate = new Date(cardDateRange.split('-')[1]);
        const today = new Date();
        
        let showCard = true;
        
        // Filter by status
        if (statusFilter !== 'all' && cardStatus !== statusFilter) {
            showCard = false;
        }
        
        // Filter by date
        if (dateFilter !== 'all') {
            switch (dateFilter) {
                case 'upcoming':
                    if (startDate <= today) showCard = false;
                    break;
                case 'current':
                    if (startDate > today || endDate < today) showCard = false;
                    break;
                case 'past':
                    if (endDate >= today) showCard = false;
                    break;
            }
        }
        
        card.style.display = showCard ? 'block' : 'none';
    });
}

function updateBookingStats() {
    const bookingsData = @json($bookings);
    
    // Ensure bookings is always an array
    let bookings = [];
    if (Array.isArray(bookingsData)) {
        bookings = bookingsData;
    } else if (bookingsData && bookingsData.data && Array.isArray(bookingsData.data)) {
        bookings = bookingsData.data;
    } else if (bookingsData && bookingsData.items && Array.isArray(bookingsData.items)) {
        bookings = bookingsData.items;
    } else {
        console.warn('Bookings data is not in expected format:', bookingsData);
        bookings = [];
    }
    
    const totalBookings = bookings.length;
    const activeBookings = bookings.filter(b => b.status === 'in_progress').length;
    const pendingBookings = bookings.filter(b => b.status === 'pending').length;
    const completedBookings = bookings.filter(b => b.status === 'completed').length;

    // Update DOM elements safely
    const totalElement = document.getElementById('total-bookings');
    const activeElement = document.getElementById('active-bookings');
    const pendingElement = document.getElementById('pending-bookings');
    const completedElement = document.getElementById('completed-bookings');
    
    if (totalElement) totalElement.textContent = totalBookings;
    if (activeElement) activeElement.textContent = activeBookings;
    if (pendingElement) pendingElement.textContent = pendingBookings;
    if (completedElement) completedElement.textContent = completedBookings;
}

function cancelBooking(bookingId) {
    if (confirm('Are you sure you want to cancel this booking?')) {
        fetch(`/client/my-bookings/${bookingId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error cancelling booking:', error);
            alert('An error occurred');
        });
    }
}

function startBooking(bookingId) {
    if (confirm('Are you ready to start your trip?')) {
        fetch(`/user/bookings/${bookingId}/start`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error starting booking:', error);
            alert('An error occurred');
        });
    }
}

function completeBooking(bookingId) {
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
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error completing booking:', error);
            alert('An error occurred');
        });
    }
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
