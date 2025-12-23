@extends('layouts.app')
@section('title', 'Vehicle Map')

@section('content')
<div class="map-container-enhanced">
    <!-- Map Header -->
    <div class="map-header fade-in-up">
        <h1 class="map-title">Vehicle Tracking Map</h1>
        <p class="map-subtitle">Real-time vehicle location monitoring and fleet management</p>
        
        <!-- Map Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600" id="total-vehicles">-</div>
                <div class="text-sm text-gray-600">Total Vehicles</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600" id="active-vehicles">-</div>
                <div class="text-sm text-gray-600">Active Vehicles</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-yellow-600" id="moving-vehicles">-</div>
                <div class="text-sm text-gray-600">Moving Vehicles</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600" id="parked-vehicles">-</div>
                <div class="text-sm text-gray-600">Parked Vehicles</div>
            </div>
        </div>
    </div>

    <!-- Map Controls -->
    <div class="map-controls-enhanced">
        <button class="map-control-btn-enhanced" onclick="zoomIn()" title="Zoom In">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </button>
        <button class="map-control-btn-enhanced" onclick="zoomOut()" title="Zoom Out">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
            </svg>
        </button>
        <button class="map-control-btn-enhanced" onclick="centerMap()" title="Center Map">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
        </button>
        <button class="map-control-btn-enhanced" onclick="toggleFullscreen()" title="Fullscreen">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
            </svg>
        </button>
        <button class="map-control-btn-enhanced" onclick="refreshMap()" title="Refresh">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
        </button>
    </div>

    <!-- Map Sidebar -->
    <div class="map-sidebar-enhanced slide-in-left">
        <h2 class="map-sidebar-title">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path>
            </svg>
            Active Vehicles
        </h2>
        
        <!-- Search and Filter -->
        <div class="mb-4">
            <input type="text" 
                   id="vehicle-search" 
                   placeholder="Search vehicles..." 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <div class="mb-4">
            <select id="vehicle-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="all">All Vehicles</option>
                <option value="moving">Moving</option>
                <option value="parked">Parked</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        
        <div id="vehicle-list" class="vehicle-list-enhanced">
            <div class="text-center text-gray-500 py-8">
                <div class="loading-skeleton h-4 w-full mb-2"></div>
                <div class="loading-skeleton h-4 w-3/4 mx-auto"></div>
            </div>
        </div>
    </div>

    <!-- Map Area -->
    <div id="map" class="map-area">
        <div class="fake-map-container">
            <!-- Map Background -->
            <div class="map-background">
                <!-- Streets -->
                <div class="street street-horizontal" style="top: 20%; width: 100%;"></div>
                <div class="street street-horizontal" style="top: 40%; width: 100%;"></div>
                <div class="street street-horizontal" style="top: 60%; width: 100%;"></div>
                <div class="street street-horizontal" style="top: 80%; width: 100%;"></div>
                
                <div class="street street-vertical" style="left: 20%; height: 100%;"></div>
                <div class="street street-vertical" style="left: 40%; height: 100%;"></div>
                <div class="street street-vertical" style="left: 60%; height: 100%;"></div>
                <div class="street street-vertical" style="left: 80%; height: 100%;"></div>
                
                <!-- Buildings -->
                <div class="building" style="top: 10%; left: 10%; width: 15%; height: 15%;"></div>
                <div class="building" style="top: 10%; left: 30%; width: 15%; height: 20%;"></div>
                <div class="building" style="top: 10%; left: 50%; width: 15%; height: 18%;"></div>
                <div class="building" style="top: 10%; left: 70%; width: 15%; height: 22%;"></div>
                <div class="building" style="top: 10%; left: 85%; width: 15%; height: 16%;"></div>
                
                <div class="building" style="top: 35%; left: 10%; width: 15%; height: 18%;"></div>
                <div class="building" style="top: 35%; left: 30%; width: 15%; height: 14%;"></div>
                <div class="building" style="top: 35%; left: 50%; width: 15%; height: 20%;"></div>
                <div class="building" style="top: 35%; left: 70%; width: 15%; height: 16%;"></div>
                <div class="building" style="top: 35%; left: 85%; width: 15%; height: 19%;"></div>
                
                <div class="building" style="top: 60%; left: 10%; width: 15%; height: 20%;"></div>
                <div class="building" style="top: 60%; left: 30%; width: 15%; height: 17%;"></div>
                <div class="building" style="top: 60%; left: 50%; width: 15%; height: 15%;"></div>
                <div class="building" style="top: 60%; left: 70%; width: 15%; height: 21%;"></div>
                <div class="building" style="top: 60%; left: 85%; width: 15%; height: 18%;"></div>
                
                <div class="building" style="top: 85%; left: 10%; width: 15%; height: 14%;"></div>
                <div class="building" style="top: 85%; left: 30%; width: 15%; height: 20%;"></div>
                <div class="building" style="top: 85%; left: 50%; width: 15%; height: 16%;"></div>
                <div class="building" style="top: 85%; left: 70%; width: 15%; height: 19%;"></div>
                <div class="building" style="top: 85%; left: 85%; width: 15%; height: 17%;"></div>
                
                <!-- Parks -->
                <div class="park" style="top: 25%; left: 25%; width: 20%; height: 20%;"></div>
                <div class="park" style="top: 70%; left: 75%; width: 15%; height: 15%;"></div>
                
                <!-- Vehicle Markers -->
                <div id="vehicle-markers"></div>
            </div>
        </div>
    </div>

    <!-- Map Legend -->
    <div class="map-legend-enhanced slide-in-right">
        <h4 class="map-legend-title">Map Legend</h4>
        <div class="legend-item-enhanced">
            <div class="legend-icon-enhanced vehicle-marker"></div>
            <span>Active Vehicle</span>
        </div>
        <div class="legend-item-enhanced">
            <div class="legend-icon-enhanced building-icon"></div>
            <span>Building</span>
        </div>
        <div class="legend-item-enhanced">
            <div class="legend-icon-enhanced park-icon"></div>
            <span>Park</span>
        </div>
        <div class="legend-item-enhanced">
            <div class="legend-icon-enhanced" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);"></div>
            <span>Moving Vehicle</span>
        </div>
        <div class="legend-item-enhanced">
            <div class="legend-icon-enhanced" style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);"></div>
            <span>Parked Vehicle</span>
        </div>
    </div>
</div>

<script>
// Enhanced Map Functionality
let vehicles = [];
let selectedVehicle = null;
let mapScale = 1;
let mapOffsetX = 0;
let mapOffsetY = 0;
let isDragging = false;
let lastMouseX = 0;
let lastMouseY = 0;

document.addEventListener('DOMContentLoaded', function() {
    initializeEnhancedMap();
});

function initializeEnhancedMap() {
    loadVehicles();
    bindMapEvents();
    bindSearchEvents();
    startRealTimeUpdates();
}

function bindMapEvents() {
    const map = document.querySelector('.map-background');
    
    // Mouse events for dragging
    map.addEventListener('mousedown', startDrag);
    document.addEventListener('mousemove', drag);
    document.addEventListener('mouseup', endDrag);
    
    // Touch events for mobile
    map.addEventListener('touchstart', startTouch);
    document.addEventListener('touchmove', touchMove);
    document.addEventListener('touchend', endTouch);
    
    // Wheel events for zooming
    map.addEventListener('wheel', handleWheel);
}

function bindSearchEvents() {
    const searchInput = document.getElementById('vehicle-search');
    const filterSelect = document.getElementById('vehicle-filter');
    
    searchInput.addEventListener('input', filterVehicles);
    filterSelect.addEventListener('change', filterVehicles);
}

function startDrag(e) {
    isDragging = true;
    lastMouseX = e.clientX;
    lastMouseY = e.clientY;
    e.preventDefault();
}

function drag(e) {
    if (!isDragging) return;
    
    const deltaX = e.clientX - lastMouseX;
    const deltaY = e.clientY - lastMouseY;
    
    mapOffsetX += deltaX;
    mapOffsetY += deltaY;
    
    updateMapTransform();
    
    lastMouseX = e.clientX;
    lastMouseY = e.clientY;
}

function endDrag() {
    isDragging = false;
}

function startTouch(e) {
    if (e.touches.length === 1) {
        isDragging = true;
        lastMouseX = e.touches[0].clientX;
        lastMouseY = e.touches[0].clientY;
    }
}

function touchMove(e) {
    if (!isDragging || e.touches.length !== 1) return;
    
    const deltaX = e.touches[0].clientX - lastMouseX;
    const deltaY = e.touches[0].clientY - lastMouseY;
    
    mapOffsetX += deltaX;
    mapOffsetY += deltaY;
    
    updateMapTransform();
    
    lastMouseX = e.touches[0].clientX;
    lastMouseY = e.touches[0].clientY;
}

function endTouch() {
    isDragging = false;
}

function handleWheel(e) {
    e.preventDefault();
    
    const delta = e.deltaY > 0 ? -0.1 : 0.1;
    mapScale = Math.max(0.5, Math.min(3, mapScale + delta));
    
    updateMapTransform();
}

function updateMapTransform() {
    const map = document.querySelector('.map-background');
    map.style.transform = `scale(${mapScale}) translate(${mapOffsetX / mapScale}px, ${mapOffsetY / mapScale}px)`;
}

function loadVehicles() {
    const listEl = document.getElementById('vehicle-list');
    
    fetch('/api/v1/locations/current')
        .then(response => response.json())
        .then(data => {
            if (data && data.success && Array.isArray(data.data)) {
                vehicles = data.data;
                renderVehicleList(vehicles);
                addVehicleMarkersToMap(vehicles);
                updateMapStatistics(vehicles);
            } else {
                showNoVehicles();
            }
        })
        .catch(error => {
            console.error('Error loading vehicles:', error);
            showNoVehicles();
        });
}

function renderVehicleList(vehiclesToShow) {
    const listEl = document.getElementById('vehicle-list');
    listEl.innerHTML = '';
    
    if (!vehiclesToShow || vehiclesToShow.length === 0) {
        showNoVehicles();
        return;
    }

    vehiclesToShow.forEach(function(vehicle) {
        const item = document.createElement('div');
        item.className = 'vehicle-item-enhanced';
        item.dataset.vehicleId = vehicle.id;
        
        const speed = vehicle.latest_location ? vehicle.latest_location.speed : 0;
        const isMoving = speed > 5;
        
        const vehicleIconHtml = vehicle.image_url ? 
            `<img src="${vehicle.image_url}" alt="${vehicle.vehicle_number || 'Unknown'}" class="vehicle-item-icon" style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px;" onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">` : 
            '';
        const vehicleIconFallbackHtml = vehicle.image_url ? 
            `<div class="vehicle-item-icon" style="display: none; font-size: 1.5rem;">${getVehicleIcon(vehicle.vehicle_type)}</div>` : 
            `<div class="vehicle-item-icon" style="font-size: 1.5rem;">${getVehicleIcon(vehicle.vehicle_type)}</div>`;
        
        item.innerHTML = `
            <div class="vehicle-item-header">
                ${vehicleIconHtml}${vehicleIconFallbackHtml}
                <div class="vehicle-item-info">
                    <h4>${vehicle.vehicle_number || 'Unknown'}</h4>
                    <p>${vehicle.driver_name || 'No driver'}</p>
                </div>
            </div>
            <div class="vehicle-item-details">
                <div class="vehicle-item-detail">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>${vehicle.vehicle_type || 'Unknown'}</span>
                </div>
                <div class="vehicle-item-detail">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <span>${speed} km/h</span>
                </div>
                <div class="vehicle-item-detail">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>${isMoving ? 'Moving' : 'Parked'}</span>
                </div>
                <div class="vehicle-item-detail">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>${vehicle.latest_location ? new Date(vehicle.latest_location.recorded_at).toLocaleTimeString() : 'N/A'}</span>
                </div>
            </div>
        `;

        item.addEventListener('click', function() {
            selectVehicle(vehicle);
            highlightVehicleOnMap(vehicle);
        });

        listEl.appendChild(item);
    });
}

function filterVehicles() {
    const searchTerm = document.getElementById('vehicle-search').value.toLowerCase();
    const filterValue = document.getElementById('vehicle-filter').value;
    
    let filteredVehicles = vehicles;
    
    // Filter by search term
    if (searchTerm) {
        filteredVehicles = filteredVehicles.filter(vehicle => 
            vehicle.vehicle_number.toLowerCase().includes(searchTerm) ||
            vehicle.driver_name.toLowerCase().includes(searchTerm) ||
            vehicle.vehicle_type.toLowerCase().includes(searchTerm)
        );
    }
    
    // Filter by status
    if (filterValue !== 'all') {
        filteredVehicles = filteredVehicles.filter(vehicle => {
            const speed = vehicle.latest_location ? vehicle.latest_location.speed : 0;
            const isMoving = speed > 5;
            
            switch (filterValue) {
                case 'moving':
                    return isMoving;
                case 'parked':
                    return !isMoving;
                case 'active':
                    return vehicle.is_active;
                case 'inactive':
                    return !vehicle.is_active;
                default:
                    return true;
            }
        });
    }
    
    renderVehicleList(filteredVehicles);
}

function showNoVehicles() {
    const listEl = document.getElementById('vehicle-list');
    listEl.innerHTML = `
        <div class="text-center text-gray-500 py-8">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <p>No vehicles found</p>
        </div>
    `;
}

function selectVehicle(vehicle) {
    // Remove active class from all items
    document.querySelectorAll('.vehicle-item-enhanced').forEach(item => {
        item.classList.remove('active');
    });
    
    // Add active class to selected item
    const selectedItem = document.querySelector(`[data-vehicle-id="${vehicle.id}"]`);
    if (selectedItem) {
        selectedItem.classList.add('active');
    }
    
    selectedVehicle = vehicle;
    showVehicleDetails(vehicle);
}

function showVehicleDetails(vehicle) {
    // Remove existing popup
    const existingPopup = document.querySelector('.vehicle-popup');
    if (existingPopup) {
        existingPopup.remove();
    }

    const popup = document.createElement('div');
    popup.className = 'vehicle-popup';
    popup.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        max-width: 400px;
        text-align: center;
        border: 2px solid rgba(30, 58, 138, 0.2);
    `;

    let locationInfo = 'No location data';
    if (vehicle.latest_location) {
        const lat = parseFloat(vehicle.latest_location.latitude);
        const lng = parseFloat(vehicle.latest_location.longitude);
        locationInfo = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
    }

    const speed = vehicle.latest_location ? vehicle.latest_location.speed : 0;
    const isMoving = speed > 5;

    popup.innerHTML = `
        <div class="mb-4">
            ${vehicle.image_url ? 
                `<img src="${vehicle.image_url}" alt="${vehicle.vehicle_number || 'Unknown'}" class="w-full h-32 object-cover rounded-lg mb-2" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">` : 
                ''
            }
            <div class="text-4xl mb-2" style="display: ${vehicle.image_url ? 'none' : 'block'}">${getVehicleIcon(vehicle.vehicle_type)}</div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">${vehicle.vehicle_number || 'Unknown'}</h3>
            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${isMoving ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                ${isMoving ? 'Moving' : 'Parked'}
            </div>
        </div>
        
        <div class="text-left space-y-2 mb-6">
            <p class="text-gray-700"><strong>Driver:</strong> ${vehicle.driver_name || 'Unknown'}</p>
            <p class="text-gray-700"><strong>Type:</strong> ${vehicle.vehicle_type || 'Unknown'}</p>
            <p class="text-gray-700"><strong>Location:</strong> ${locationInfo}</p>
            <p class="text-gray-700"><strong>Speed:</strong> ${speed} km/h</p>
            <p class="text-gray-700"><strong>Status:</strong> ${vehicle.is_active ? 'Active' : 'Inactive'}</p>
            <p class="text-gray-700"><strong>Last Update:</strong> ${vehicle.latest_location ? new Date(vehicle.latest_location.recorded_at).toLocaleString() : 'N/A'}</p>
        </div>
        
        <div class="flex gap-2">
            <button onclick="this.parentElement.parentElement.remove()" class="flex-1 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200">
                Close
            </button>
            <button onclick="trackVehicle('${vehicle.id}')" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                Track
            </button>
        </div>
    `;

    document.body.appendChild(popup);

    // Close popup when clicking outside
    popup.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    document.addEventListener('click', function() {
        popup.remove();
    }, { once: true });
}

function addVehicleMarkersToMap(vehicles) {
    const markersContainer = document.getElementById('vehicle-markers');
    markersContainer.innerHTML = '';
    
    // Predefined positions for better distribution
    const positions = [
        { top: '15%', left: '25%' },
        { top: '35%', left: '45%' },
        { top: '55%', left: '15%' },
        { top: '75%', left: '35%' },
        { top: '25%', left: '65%' },
        { top: '45%', left: '75%' },
        { top: '65%', left: '55%' },
        { top: '85%', left: '65%' },
        { top: '5%', left: '85%' },
        { top: '95%', left: '15%' }
    ];
    
    vehicles.forEach(function(vehicle, index) {
        const position = positions[index % positions.length];
        const marker = document.createElement('div');
        marker.className = 'vehicle-marker-enhanced';
        
        const speed = vehicle.latest_location ? vehicle.latest_location.speed : 0;
        const isMoving = speed > 5;
        
        marker.style.cssText = `
            position: absolute;
            top: ${position.top};
            left: ${position.left};
            width: 50px;
            height: 50px;
            background: ${isMoving ? 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)' : 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)'};
            border-radius: 12px;
            border: 3px solid white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
            animation: ${isMoving ? 'pulse-moving' : 'pulse'} 2s infinite;
            z-index: 10;
            transform: rotate(-15deg);
            transition: all 0.3s ease;
        `;
        
        // Use vehicle image if available, otherwise use icon
        if (vehicle.image_url) {
            marker.innerHTML = `<img src="${vehicle.image_url}" alt="${vehicle.vehicle_number}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 12px;" onerror="this.onerror=null; this.src=''; this.parentElement.innerHTML='${getVehicleIcon(vehicle.vehicle_type)}';">`;
        } else {
            marker.innerHTML = getVehicleIcon(vehicle.vehicle_type);
        }
        marker.title = `${vehicle.vehicle_number} - ${vehicle.driver_name} (${speed} km/h)`;
        
        marker.addEventListener('click', function() {
            selectVehicle(vehicle);
            highlightVehicleOnMap(vehicle);
        });
        
        marker.addEventListener('mouseenter', function() {
            this.style.transform = 'rotate(0deg) scale(1.2)';
            this.style.zIndex = '20';
        });
        
        marker.addEventListener('mouseleave', function() {
            this.style.transform = 'rotate(-15deg) scale(1)';
            this.style.zIndex = '10';
        });
        
        markersContainer.appendChild(marker);
    });
}

function highlightVehicleOnMap(vehicle) {
    // Remove existing highlights
    document.querySelectorAll('.vehicle-marker-enhanced').forEach(marker => {
        const speed = vehicle.latest_location ? vehicle.latest_location.speed : 0;
        const isMoving = speed > 5;
        marker.style.background = isMoving ? 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)' : 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
        marker.style.transform = 'rotate(-15deg) scale(1)';
        marker.style.borderColor = 'white';
    });
    
    // Highlight the selected vehicle
    const markers = document.querySelectorAll('.vehicle-marker-enhanced');
    markers.forEach(marker => {
        if (marker.title.includes(vehicle.vehicle_number)) {
            marker.style.background = 'linear-gradient(135deg, #1e3a8a 0%, #3730a3 100%)';
            marker.style.transform = 'rotate(0deg) scale(1.4)';
            marker.style.borderColor = '#1e3a8a';
            marker.style.zIndex = '30';
            marker.style.boxShadow = '0 6px 25px rgba(30, 58, 138, 0.6)';
        }
    });
}

function updateMapStatistics(vehicles) {
    const totalVehicles = vehicles.length;
    const activeVehicles = vehicles.filter(v => v.is_active).length;
    const movingVehicles = vehicles.filter(v => v.latest_location && v.latest_location.speed > 5).length;
    const parkedVehicles = totalVehicles - movingVehicles;

    document.getElementById('total-vehicles').textContent = totalVehicles;
    document.getElementById('active-vehicles').textContent = activeVehicles;
    document.getElementById('moving-vehicles').textContent = movingVehicles;
    document.getElementById('parked-vehicles').textContent = parkedVehicles;
}

function getVehicleIcon(type) {
    const icons = {
        'Car': '🚙',
        'Truck': '🚛',
        'Van': '🚐',
        'Motorcycle': '🏍️',
        'Bus': '🚌',
        'Taxi': '🚕',
        'SUV': '🚗',
        'Sedan': '🚘',
        'Pickup': '🛻',
        'Emergency': '🚑'
    };
    return icons[type] || '🚙';
}

// Map Control Functions
function zoomIn() {
    mapScale = Math.min(mapScale + 0.2, 3);
    updateMapTransform();
}

function zoomOut() {
    mapScale = Math.max(mapScale - 0.2, 0.5);
    updateMapTransform();
}

function centerMap() {
    mapScale = 1;
    mapOffsetX = 0;
    mapOffsetY = 0;
    updateMapTransform();
}

function toggleFullscreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
    } else {
        document.exitFullscreen();
    }
}

function refreshMap() {
    loadVehicles();
    showMessage('Map refreshed!', 'success');
}

function trackVehicle(vehicleId) {
    const vehicle = vehicles.find(v => v.id == vehicleId);
    if (vehicle) {
        highlightVehicleOnMap(vehicle);
        showMessage(`Tracking ${vehicle.vehicle_number}`, 'success');
    }
}

function startRealTimeUpdates() {
    // Update vehicle data every 30 seconds
    setInterval(() => {
        loadVehicles();
    }, 30000);
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
/* Enhanced Map Styles */
.map-container-enhanced {
    position: relative;
    min-height: 100vh;
    background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 100%);
    overflow: hidden;
}

.map-area {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
}

.fake-map-container {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background: linear-gradient(135deg, #a8e6cf 0%, #88d8c0 100%);
}

.map-background {
    position: relative;
    width: 100%;
    height: 100%;
    transform-origin: center center;
    transition: transform 0.3s ease;
    cursor: grab;
}

.map-background:active {
    cursor: grabbing;
}

.street {
    position: absolute;
    background: #666;
    box-shadow: 0 0 5px rgba(0,0,0,0.3);
}

.street-horizontal {
    height: 8px;
    border-top: 2px solid #fff;
    border-bottom: 2px solid #fff;
}

.street-vertical {
    width: 8px;
    border-left: 2px solid #fff;
    border-right: 2px solid #fff;
}

.building {
    position: absolute;
    background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
    border: 2px solid #5d6d7e;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    border-radius: 2px;
}

.building::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: repeating-linear-gradient(
        0deg,
        transparent,
        transparent 3px,
        rgba(255,255,255,0.1) 3px,
        rgba(255,255,255,0.1) 6px
    );
}

.park {
    position: absolute;
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
    border: 2px solid #1e8449;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.park::before {
    content: '🌳';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 1.5rem;
    opacity: 0.7;
}

.vehicle-marker-enhanced {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 4px 20px rgba(0,0,0,0.3), 0 0 0 0 rgba(239, 68, 68, 0.7);
    }
    70% {
        box-shadow: 0 4px 20px rgba(0,0,0,0.3), 0 0 0 10px rgba(239, 68, 68, 0);
    }
    100% {
        box-shadow: 0 4px 20px rgba(0,0,0,0.3), 0 0 0 0 rgba(239, 68, 68, 0);
    }
}

@keyframes pulse-moving {
    0% {
        box-shadow: 0 4px 20px rgba(0,0,0,0.3), 0 0 0 0 rgba(245, 158, 11, 0.7);
    }
    70% {
        box-shadow: 0 4px 20px rgba(0,0,0,0.3), 0 0 0 10px rgba(245, 158, 11, 0);
    }
    100% {
        box-shadow: 0 4px 20px rgba(0,0,0,0.3), 0 0 0 0 rgba(245, 158, 11, 0);
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .map-sidebar-enhanced {
        width: calc(100% - 2rem);
        position: relative;
        margin-bottom: 1rem;
    }
    
    .map-controls-enhanced {
        position: relative;
        flex-direction: row;
        justify-content: center;
        margin-bottom: 1rem;
    }
    
    .map-legend-enhanced {
        position: relative;
        margin-top: 1rem;
    }
    
    .map-header {
        margin: 0.5rem;
        padding: 1rem;
    }
    
    .map-title {
        font-size: 1.5rem;
    }
}
</style>
@endsection