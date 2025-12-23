@extends('layouts.admin')
@section('title', 'Original Dashboard')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-6 fade-in-up">
    <!-- Header -->
    <div class="dashboard-header">
        <h1>Original Dashboard</h1>
        <p>Welcome back, {{ auth()->user()->name }}! This is the original dashboard with map functionality.</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-icon primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path>
                </svg>
            </div>
            <h3 class="stat-value">{{ \App\Models\Vehicle::count() }}</h3>
            <p class="stat-label">Total Vehicles</p>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <h3 class="stat-value">{{ \App\Models\Booking::count() }}</h3>
            <p class="stat-label">Total Bookings</p>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon warning">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="stat-value">{{ \App\Models\User::where('role', 'chauffeur')->count() }}</h3>
            <p class="stat-label">Active Chauffeurs</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3>Quick Actions</h3>
        <div class="actions-grid">
            <a href="/admin/map-dashboard" class="action-card">
                <div class="action-icon blue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                </div>
                <h4 class="action-title">🗺️ Live Tracking</h4>
            </a>

            <a href="/admin/users" class="action-card">
                <div class="action-icon green">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <h4 class="action-title">👥 Team</h4>
            </a>

            <a href="/admin/vehicles" class="action-card">
                <div class="action-icon purple">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path>
                    </svg>
                </div>
                <h4 class="action-title">🚗 Fleet</h4>
            </a>

            <a href="/admin/bookings" class="action-card">
                <div class="action-icon gray">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h4 class="action-title">📅 Reservations</h4>
            </a>
        </div>
    </div>

    <!-- Map Section -->
    <div class="content-section">
        <h3>Vehicle Map</h3>
        <div class="map-container" style="height: 500px;">
            <div class="sidebar" style="width: 300px;">
                <h2>Active Vehicles</h2>
                <div id="vehicle-list" class="vehicle-list">
                    <div class="no-vehicles">Loading vehicles…</div>
                </div>
            </div>

            <div id="map" style="flex-grow: 1;">
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
                        
                        <!-- Map Controls -->
                        <div class="map-controls">
                            <button class="map-control-btn" onclick="zoomIn()" title="Zoom In">+</button>
                            <button class="map-control-btn" onclick="zoomOut()" title="Zoom Out">-</button>
                            <button class="map-control-btn" onclick="centerMap()" title="Center Map">⌂</button>
                        </div>
                        
                        <!-- Map Legend -->
                        <div class="map-legend">
                            <h4>Legend</h4>
                            <div class="legend-item">
                                <div class="legend-icon vehicle-marker"></div>
                                <span>Active Vehicle</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-icon building-icon"></div>
                                <span>Building</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-icon park-icon"></div>
                                <span>Park</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple vehicle loading without external dependencies
    document.addEventListener('DOMContentLoaded', function() {
        loadVehicles();
    });

    function loadVehicles() {
        const listEl = document.getElementById('vehicle-list');
        
        // Simple fetch without async/await for better compatibility
        fetch('/api/v1/locations/current')
            .then(response => response.json())
            .then(data => {
                if (data && data.success && Array.isArray(data.data)) {
                    renderVehicleList(data.data);
                } else {
                    showNoVehicles();
                }
            })
            .catch(error => {
                console.log('Error loading vehicles:', error);
                showNoVehicles();
            });
    }

    function renderVehicleList(vehicles) {
        const listEl = document.getElementById('vehicle-list');
        listEl.innerHTML = '';
        
        if (!vehicles || vehicles.length === 0) {
            showNoVehicles();
            return;
        }

        vehicles.forEach(function(vehicle) {
            const item = document.createElement('div');
            item.className = 'vehicle-item';
            
            const vehicleIcon = vehicle.image_url ? 
                `<img src="${vehicle.image_url}" alt="${vehicle.vehicle_number || 'Unknown'}" style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px; margin-right: 10px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">` : 
                '';
            const vehicleIconFallback = vehicle.image_url ? 
                `<div style="width: 48px; height: 48px; display: none; align-items: center; justify-content: center; font-size: 1.5rem; margin-right: 10px;">${getVehicleIcon(vehicle.vehicle_type)}</div>` : 
                `<div style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-right: 10px;">${getVehicleIcon(vehicle.vehicle_type)}</div>`;
            
            item.innerHTML = '<div style="display: flex; align-items: center;">' +
                            vehicleIcon + vehicleIconFallback +
                            '<div class="vehicle-info">' +
                            '<h4>' + (vehicle.vehicle_number || 'Unknown') + '</h4>' +
                            '<p>' + (vehicle.driver_name || 'No driver') + '</p>' +
                            '<p class="vehicle-type">' + (vehicle.vehicle_type || 'Unknown type') + '</p>' +
                            (vehicle.latest_location && vehicle.latest_location.speed ? '<span class="speed">' + vehicle.latest_location.speed + ' km/h</span>' : '') +
                            '</div></div>';

            item.addEventListener('click', function() {
                showVehicleDetails(vehicle);
                highlightVehicleOnMap(vehicle);
            });

            listEl.appendChild(item);
        });
        
        // Add vehicle markers to the map
        addVehicleMarkersToMap(vehicles);
    }

    function showNoVehicles() {
        const listEl = document.getElementById('vehicle-list');
        listEl.innerHTML = '<div style="text-align: center; color: #666; padding: 20px;">No active vehicles found</div>';
    }

    function showVehicleDetails(vehicle) {
        // Remove existing popup
        const existingPopup = document.querySelector('.vehicle-popup');
        if (existingPopup) {
            existingPopup.remove();
        }

        const popup = document.createElement('div');
        popup.className = 'vehicle-popup';
        popup.style.cssText = 'position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); z-index: 1000; max-width: 350px; text-align: center; border: 3px solid #3498db;';

        let locationInfo = 'No location data';
        if (vehicle.latest_location) {
            const lat = parseFloat(vehicle.latest_location.latitude);
            const lng = parseFloat(vehicle.latest_location.longitude);
            locationInfo = lat.toFixed(4) + ', ' + lng.toFixed(4);
        }

        const vehicleImage = vehicle.image_url ? 
            `<img src="${vehicle.image_url}" alt="${vehicle.vehicle_number || 'Unknown'}" style="width: 100%; height: 150px; object-fit: cover; border-radius: 10px; margin-bottom: 15px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">` : 
            '';
        const vehicleIconFallback = vehicle.image_url ? 
            `<div style="text-align: center; font-size: 3rem; margin-bottom: 15px; display: none;">${getVehicleIcon(vehicle.vehicle_type)}</div>` : 
            `<div style="text-align: center; font-size: 3rem; margin-bottom: 15px;">${getVehicleIcon(vehicle.vehicle_type)}</div>`;
        
        popup.innerHTML = vehicleImage + vehicleIconFallback + '<h3 style="margin: 0 0 20px 0; color: #2c3e50; font-size: 1.5rem; font-weight: 600;">' + (vehicle.vehicle_number || 'Unknown') + '</h3>' +
                         '<div style="text-align: left; margin-bottom: 20px;">' +
                         '<p style="margin: 8px 0; color: #34495e;"><strong>Driver:</strong> ' + (vehicle.driver_name || 'Unknown') + '</p>' +
                         '<p style="margin: 8px 0; color: #34495e;"><strong>Type:</strong> ' + (vehicle.vehicle_type || 'Unknown') + '</p>' +
                         '<p style="margin: 8px 0; color: #34495e;"><strong>Location:</strong> ' + locationInfo + '</p>' +
                         (vehicle.latest_location && vehicle.latest_location.speed ? '<p style="margin: 8px 0; color: #34495e;"><strong>Speed:</strong> ' + vehicle.latest_location.speed + ' km/h</p>' : '') +
                         '</div>' +
                         '<button onclick="this.parentElement.remove()" class="btn btn-primary" style="width: 100%;">Close</button>';

        document.body.appendChild(popup);

        // Close popup when clicking outside
        popup.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        
        document.addEventListener('click', function() {
            popup.remove();
        }, { once: true });
    }

    // Map Functions
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
            marker.className = 'vehicle-marker';
            marker.style.cssText = `
                position: absolute;
                top: ${position.top};
                left: ${position.left};
                width: 40px;
                height: 40px;
                background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                border-radius: 8px;
                border: 3px solid white;
                box-shadow: 0 4px 15px rgba(0,0,0,0.3);
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: bold;
                font-size: 1.2rem;
                animation: pulse 2s infinite;
                z-index: 10;
                transform: rotate(-15deg);
                transition: all 0.3s ease;
            `;
            
            // Use vehicle image if available, otherwise use icon
            if (vehicle.image_url) {
                marker.innerHTML = `<img src="${vehicle.image_url}" alt="${vehicle.vehicle_number}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;" onerror="this.onerror=null; this.src=''; this.parentElement.innerHTML='${getVehicleIcon(vehicle.vehicle_type)}';">`;
            } else {
                marker.innerHTML = getVehicleIcon(vehicle.vehicle_type);
            }
            marker.title = vehicle.vehicle_number + ' - ' + vehicle.driver_name;
            
            marker.addEventListener('click', function() {
                showVehicleDetails(vehicle);
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
        document.querySelectorAll('.vehicle-marker').forEach(marker => {
            marker.style.background = 'linear-gradient(135deg, #3498db 0%, #2980b9 100%)';
            marker.style.transform = 'rotate(-15deg) scale(1)';
            marker.style.borderColor = 'white';
        });
        
        // Highlight the selected vehicle
        const markers = document.querySelectorAll('.vehicle-marker');
        markers.forEach(marker => {
            if (marker.title.includes(vehicle.vehicle_number)) {
                marker.style.background = 'linear-gradient(135deg, #f39c12 0%, #e67e22 100%)';
                marker.style.transform = 'rotate(0deg) scale(1.4)';
                marker.style.borderColor = '#f39c12';
                marker.style.zIndex = '30';
                marker.style.boxShadow = '0 6px 20px rgba(243, 156, 18, 0.6)';
            }
        });
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

    function zoomIn() {
        const map = document.querySelector('.map-background');
        const currentScale = parseFloat(map.style.transform.replace('scale(', '').replace(')', '')) || 1;
        map.style.transform = `scale(${Math.min(currentScale + 0.2, 2)})`;
    }

    function zoomOut() {
        const map = document.querySelector('.map-background');
        const currentScale = parseFloat(map.style.transform.replace('scale(', '').replace(')', '')) || 1;
        map.style.transform = `scale(${Math.max(currentScale - 0.2, 0.5)})`;
    }

    function centerMap() {
        const map = document.querySelector('.map-background');
        map.style.transform = 'scale(1) translate(0, 0)';
    }
</script>

<style>
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

    .vehicle-marker {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 4px 15px rgba(0,0,0,0.3), 0 0 0 0 rgba(52, 152, 219, 0.7);
        }
        70% {
            box-shadow: 0 4px 15px rgba(0,0,0,0.3), 0 0 0 10px rgba(52, 152, 219, 0);
        }
        100% {
            box-shadow: 0 4px 15px rgba(0,0,0,0.3), 0 0 0 0 rgba(52, 152, 219, 0);
        }
    }

    .map-controls {
        position: absolute;
        top: 20px;
        right: 20px;
        display: flex;
        flex-direction: column;
        gap: 5px;
        z-index: 100;
    }

    .map-control-btn {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.2rem;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        transition: all 0.3s ease;
    }

    .map-control-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
    }

    .map-legend {
        position: absolute;
        bottom: 20px;
        left: 20px;
        background: rgba(255, 255, 255, 0.95);
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        z-index: 100;
        min-width: 150px;
    }

    .map-legend h4 {
        margin: 0 0 10px 0;
        color: #2c3e50;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 5px;
        font-size: 0.8rem;
        color: #34495e;
    }

    .legend-icon {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .legend-icon.vehicle-marker {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        border: 2px solid white;
    }

    .legend-icon.building-icon {
        background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
        border-radius: 2px;
    }

    .legend-icon.park-icon {
        background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
        border-radius: 4px;
    }

    .vehicle-item {
        background-color: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.5rem;
    }

    .vehicle-item:hover {
        background-color: #e0f2fe;
        border-color: #3b82f6;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .vehicle-info h4 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.25rem;
    }

    .vehicle-info p {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .vehicle-type {
        font-size: 0.75rem;
        color: #9ca3af;
        background-color: #e2e8f0;
        padding: 0.25rem 0.5rem;
        border-radius: 0.5rem;
        display: inline-block;
        margin-top: 0.5rem;
    }

    .speed {
        font-size: 0.875rem;
        font-weight: 600;
        color: #2563eb;
        margin-left: auto;
    }
</style>
@endsection
