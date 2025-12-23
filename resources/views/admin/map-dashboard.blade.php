@extends('layouts.admin')
@section('title', 'Vehicle Map Dashboard')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-6 fade-in-up">
    <!-- Header -->
    <div class="dashboard-header">
        <h1>Vehicle Map Dashboard</h1>
        <p>Real-time vehicle tracking and location monitoring system.</p>
    </div>

    <!-- Map Container -->
    <div class="modern-card p-0 overflow-hidden">
        <div class="map-container" style="height: 600px; position: relative;">
            <!-- Sidebar -->
            <div class="sidebar" style="position: absolute; left: 0; top: 0; width: 300px; height: 100%; background: white; border-right: 1px solid #e5e7eb; z-index: 10;">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path>
                        </svg>
                        Active Vehicles
                    </h2>
                    <div id="vehicle-list" class="vehicle-list space-y-3">
                        <div class="text-center text-gray-500 py-8">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <p>Loading vehicles...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Area -->
            <div id="map" style="margin-left: 300px; height: 100%; position: relative;">
                <div class="fake-map-container" style="width: 100%; height: 100%; overflow: hidden; background: linear-gradient(135deg, #a8e6cf 0%, #88d8c0 100%);">
                    <!-- Map Background -->
                    <div class="map-background" style="position: relative; width: 100%; height: 100%; transform-origin: center center; transition: transform 0.3s ease;">
                        <!-- Streets -->
                        <div class="street street-horizontal" style="position: absolute; top: 20%; width: 100%; height: 8px; background: #666; box-shadow: 0 0 5px rgba(0,0,0,0.3); border-top: 2px solid #fff; border-bottom: 2px solid #fff;"></div>
                        <div class="street street-horizontal" style="position: absolute; top: 40%; width: 100%; height: 8px; background: #666; box-shadow: 0 0 5px rgba(0,0,0,0.3); border-top: 2px solid #fff; border-bottom: 2px solid #fff;"></div>
                        <div class="street street-horizontal" style="position: absolute; top: 60%; width: 100%; height: 8px; background: #666; box-shadow: 0 0 5px rgba(0,0,0,0.3); border-top: 2px solid #fff; border-bottom: 2px solid #fff;"></div>
                        <div class="street street-horizontal" style="position: absolute; top: 80%; width: 100%; height: 8px; background: #666; box-shadow: 0 0 5px rgba(0,0,0,0.3); border-top: 2px solid #fff; border-bottom: 2px solid #fff;"></div>
                        
                        <div class="street street-vertical" style="position: absolute; left: 20%; height: 100%; width: 8px; background: #666; box-shadow: 0 0 5px rgba(0,0,0,0.3); border-left: 2px solid #fff; border-right: 2px solid #fff;"></div>
                        <div class="street street-vertical" style="position: absolute; left: 40%; height: 100%; width: 8px; background: #666; box-shadow: 0 0 5px rgba(0,0,0,0.3); border-left: 2px solid #fff; border-right: 2px solid #fff;"></div>
                        <div class="street street-vertical" style="position: absolute; left: 60%; height: 100%; width: 8px; background: #666; box-shadow: 0 0 5px rgba(0,0,0,0.3); border-left: 2px solid #fff; border-right: 2px solid #fff;"></div>
                        <div class="street street-vertical" style="position: absolute; left: 80%; height: 100%; width: 8px; background: #666; box-shadow: 0 0 5px rgba(0,0,0,0.3); border-left: 2px solid #fff; border-right: 2px solid #fff;"></div>
                        
                        <!-- Buildings -->
                        <div class="building" style="position: absolute; top: 10%; left: 10%; width: 15%; height: 15%; background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); border: 2px solid #5d6d7e; box-shadow: 0 2px 8px rgba(0,0,0,0.2); border-radius: 2px;"></div>
                        <div class="building" style="position: absolute; top: 10%; left: 30%; width: 15%; height: 20%; background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); border: 2px solid #5d6d7e; box-shadow: 0 2px 8px rgba(0,0,0,0.2); border-radius: 2px;"></div>
                        <div class="building" style="position: absolute; top: 10%; left: 50%; width: 15%; height: 18%; background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); border: 2px solid #5d6d7e; box-shadow: 0 2px 8px rgba(0,0,0,0.2); border-radius: 2px;"></div>
                        <div class="building" style="position: absolute; top: 10%; left: 70%; width: 15%; height: 22%; background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); border: 2px solid #5d6d7e; box-shadow: 0 2px 8px rgba(0,0,0,0.2); border-radius: 2px;"></div>
                        <div class="building" style="position: absolute; top: 10%; left: 85%; width: 15%; height: 16%; background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); border: 2px solid #5d6d7e; box-shadow: 0 2px 8px rgba(0,0,0,0.2); border-radius: 2px;"></div>
                        
                        <!-- Parks -->
                        <div class="park" style="position: absolute; top: 25%; left: 25%; width: 20%; height: 20%; background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%); border: 2px solid #1e8449; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.2);"></div>
                        <div class="park" style="position: absolute; top: 70%; left: 75%; width: 15%; height: 15%; background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%); border: 2px solid #1e8449; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.2);"></div>
                        
                        <!-- Vehicle Markers -->
                        <div id="vehicle-markers" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></div>
                        
                        <!-- Map Controls -->
                        <div class="map-controls" style="position: absolute; top: 20px; right: 20px; display: flex; flex-direction: column; gap: 5px; z-index: 100;">
                            <button class="map-control-btn" onclick="zoomIn()" title="Zoom In" style="width: 40px; height: 40px; background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; border: none; border-radius: 8px; font-size: 1.2rem; font-weight: bold; cursor: pointer; box-shadow: 0 2px 10px rgba(0,0,0,0.3); transition: all 0.3s ease;">+</button>
                            <button class="map-control-btn" onclick="zoomOut()" title="Zoom Out" style="width: 40px; height: 40px; background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; border: none; border-radius: 8px; font-size: 1.2rem; font-weight: bold; cursor: pointer; box-shadow: 0 2px 10px rgba(0,0,0,0.3); transition: all 0.3s ease;">-</button>
                            <button class="map-control-btn" onclick="centerMap()" title="Center Map" style="width: 40px; height: 40px; background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; border: none; border-radius: 8px; font-size: 1.2rem; font-weight: bold; cursor: pointer; box-shadow: 0 2px 10px rgba(0,0,0,0.3); transition: all 0.3s ease;">⌂</button>
                        </div>
                        
                        <!-- Map Legend -->
                        <div class="map-legend" style="position: absolute; bottom: 20px; left: 20px; background: rgba(255, 255, 255, 0.95); padding: 15px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.2); z-index: 100; min-width: 150px;">
                            <h4 style="margin: 0 0 10px 0; color: #2c3e50; font-size: 0.9rem; font-weight: 600;">Legend</h4>
                            <div class="legend-item" style="display: flex; align-items: center; gap: 8px; margin-bottom: 5px; font-size: 0.8rem; color: #34495e;">
                                <div class="legend-icon vehicle-marker" style="width: 16px; height: 16px; border-radius: 50%; flex-shrink: 0; background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border: 2px solid white;"></div>
                                <span>Active Vehicle</span>
                            </div>
                            <div class="legend-item" style="display: flex; align-items: center; gap: 8px; margin-bottom: 5px; font-size: 0.8rem; color: #34495e;">
                                <div class="legend-icon building-icon" style="width: 16px; height: 16px; border-radius: 2px; flex-shrink: 0; background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);"></div>
                                <span>Building</span>
                            </div>
                            <div class="legend-item" style="display: flex; align-items: center; gap: 8px; margin-bottom: 5px; font-size: 0.8rem; color: #34495e;">
                                <div class="legend-icon park-icon" style="width: 16px; height: 16px; border-radius: 4px; flex-shrink: 0; background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);"></div>
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
document.addEventListener('DOMContentLoaded', function() {
    loadVehicles();
});

function loadVehicles() {
    const listEl = document.getElementById('vehicle-list');
    
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
        item.className = 'modern-card p-4 cursor-pointer hover:shadow-md transition duration-200';
        
        const vehicleIcon = vehicle.image_url ? 
            `<img src="${vehicle.image_url}" alt="${vehicle.vehicle_number || 'Unknown'}" class="w-10 h-10 rounded-lg object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">` : 
            '';
        const vehicleIconFallback = vehicle.image_url ? 
            `<div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center hidden"><div class="text-xl">${getVehicleIcon(vehicle.vehicle_type)}</div></div>` : 
            `<div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center"><div class="text-xl">${getVehicleIcon(vehicle.vehicle_type)}</div></div>`;
        
        item.innerHTML = '<div class="flex items-center space-x-3">' +
                        vehicleIcon + vehicleIconFallback +
                        '<div class="flex-1">' +
                        '<h4 class="font-semibold text-gray-900">' + (vehicle.vehicle_number || 'Unknown') + '</h4>' +
                        '<p class="text-sm text-gray-600">' + (vehicle.driver_name || 'No driver') + '</p>' +
                        '<p class="text-xs text-gray-500">' + (vehicle.vehicle_type || 'Unknown type') + '</p>' +
                        (vehicle.latest_location && vehicle.latest_location.speed ? '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">' + vehicle.latest_location.speed + ' km/h</span>' : '') +
                        '</div></div>';

        item.addEventListener('click', function() {
            showVehicleDetails(vehicle);
            highlightVehicleOnMap(vehicle);
        });

        listEl.appendChild(item);
    });
    
    addVehicleMarkersToMap(vehicles);
}

function showNoVehicles() {
    const listEl = document.getElementById('vehicle-list');
    listEl.innerHTML = '<div class="text-center text-gray-500 py-8"><svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path></svg><p>No active vehicles found</p></div>';
}

function showVehicleDetails(vehicle) {
    const existingPopup = document.querySelector('.vehicle-popup');
    if (existingPopup) {
        existingPopup.remove();
    }

    const popup = document.createElement('div');
    popup.className = 'vehicle-popup';
    popup.style.cssText = 'position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 24px; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); z-index: 1000; max-width: 400px; text-align: center; border: 1px solid #e5e7eb;';

    let locationInfo = 'No location data';
    if (vehicle.latest_location) {
        const lat = parseFloat(vehicle.latest_location.latitude);
        const lng = parseFloat(vehicle.latest_location.longitude);
        locationInfo = lat.toFixed(4) + ', ' + lng.toFixed(4);
    }

    const vehicleImage = vehicle.image_url ? 
        `<img src="${vehicle.image_url}" alt="${vehicle.vehicle_number || 'Unknown'}" class="w-full h-32 object-cover rounded-lg mb-4" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">` : 
        '';
    const vehicleIconFallback = vehicle.image_url ? 
        `<div class="w-full h-32 bg-blue-100 rounded-lg mb-4 flex items-center justify-center hidden"><div class="text-4xl">${getVehicleIcon(vehicle.vehicle_type)}</div></div>` : 
        `<div class="flex items-center justify-center mb-4"><div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center"><div class="text-3xl">${getVehicleIcon(vehicle.vehicle_type)}</div></div></div>`;
    
    popup.innerHTML = vehicleImage + vehicleIconFallback +
                     '<h3 class="text-xl font-semibold text-gray-900 mb-4">' + (vehicle.vehicle_number || 'Unknown') + '</h3>' +
                     '<div class="text-left space-y-2 mb-6">' +
                     '<p class="text-gray-700"><strong>Driver:</strong> ' + (vehicle.driver_name || 'Unknown') + '</p>' +
                     '<p class="text-gray-700"><strong>Type:</strong> ' + (vehicle.vehicle_type || 'Unknown') + '</p>' +
                     '<p class="text-gray-700"><strong>Location:</strong> ' + locationInfo + '</p>' +
                     (vehicle.latest_location && vehicle.latest_location.speed ? '<p class="text-gray-700"><strong>Speed:</strong> ' + vehicle.latest_location.speed + ' km/h</p>' : '') +
                     '</div>' +
                     '<button onclick="this.parentElement.remove()" class="w-full px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition duration-200">Close</button>';

    document.body.appendChild(popup);

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
    document.querySelectorAll('.vehicle-marker').forEach(marker => {
        marker.style.background = 'linear-gradient(135deg, #3498db 0%, #2980b9 100%)';
        marker.style.transform = 'rotate(-15deg) scale(1)';
        marker.style.borderColor = 'white';
    });
    
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

.map-control-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
}
</style>
@endsection
