@extends('layouts.app')
@section('title', 'Fleet Management')

@section('content')
<link href="{{ asset('css/fleet-modern.css') }}" rel="stylesheet">
<div class="fleet-modern-container admin-fleet-modern">
    <div class="max-w-7xl mx-auto p-6 space-y-6 animate-slide-in-up">
    <!-- Header -->
    <div class="fleet-modern-header">
        <h1 class="fleet-modern-title">Fleet Management</h1>
        <p class="fleet-modern-subtitle">Manage your vehicle fleet with comprehensive tracking and analytics</p>
        
        <!-- Fleet Statistics -->
        <div class="fleet-stats-modern">
            <div class="fleet-stat-modern-card">
                <div class="fleet-stat-modern-number" id="total-vehicles">-</div>
                <div class="fleet-stat-modern-label">Total Vehicles</div>
            </div>
            <div class="fleet-stat-modern-card">
                <div class="fleet-stat-modern-number" id="active-vehicles">-</div>
                <div class="fleet-stat-modern-label">Active Vehicles</div>
            </div>
            <div class="fleet-stat-modern-card">
                <div class="fleet-stat-modern-number" id="assigned-vehicles">-</div>
                <div class="fleet-stat-modern-label">Assigned Vehicles</div>
            </div>
            <div class="fleet-stat-modern-card">
                <div class="fleet-stat-modern-number" id="unassigned-vehicles">-</div>
                <div class="fleet-stat-modern-label">Unassigned Vehicles</div>
            </div>
        </div>
        
        <!-- Fleet Actions -->
        <div class="fleet-actions-modern">
            @auth
                @if(auth()->user()->role === 'admin')
                    <button id="toggle-form" class="fleet-btn-modern fleet-btn-modern-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Vehicle
                    </button>
                @endif
            @endauth
            <a href="/map" class="fleet-btn-modern fleet-btn-modern-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                View Map
            </a>
            <button id="refresh-vehicles" class="fleet-btn-modern fleet-btn-modern-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Vehicle Form -->
    <div id="vehicle-form-wrapper" class="modern-card p-6" style="display:none;">
        <h2 id="form-title" class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
            <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path>
            </svg>
            Add New Vehicle
        </h2>
        
        <form id="vehicle-form" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="vehicle_number" class="block text-sm font-medium text-gray-700 mb-2">Vehicle Number *</label>
                    <input type="text" 
                           name="vehicle_number" 
                           id="vehicle_number"
                           placeholder="e.g., TUN-1234"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                           required>
          </div>

                <div>
                    <label for="driver_name" class="block text-sm font-medium text-gray-700 mb-2">Driver Name *</label>
                    <input type="text" 
                           name="driver_name" 
                           id="driver_name"
                           placeholder="Enter driver name"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                           required>
          </div>

                <div>
                    <label for="vehicle_type" class="block text-sm font-medium text-gray-700 mb-2">Vehicle Type *</label>
                    <select name="vehicle_type" 
                            id="vehicle_type"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                            required>
              <option value="">Select type</option>
                        <option value="Sedan">🚙 Sedan</option>
                        <option value="SUV">🚗 SUV</option>
              <option value="Truck">🚛 Truck</option>
              <option value="Van">🚐 Van</option>
              <option value="Motorcycle">🏍️ Motorcycle</option>
              <option value="Bus">🚌 Bus</option>
              <option value="Taxi">🚕 Taxi</option>
                        <option value="Hatchback">🚘 Hatchback</option>
              <option value="Pickup">🛻 Pickup</option>
              <option value="Emergency">🚑 Emergency</option>
            </select>
          </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="tel" 
                           name="phone" 
                           id="phone"
                           placeholder="+216 XX XXX XXX"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                </div>
            </div>

            <!-- Vehicle Image -->
            <div>
                <label for="vehicle_image" class="block text-sm font-medium text-gray-700 mb-2">Vehicle Image</label>
            <div class="image-upload-container">
                    <input type="file" 
                           id="vehicle_image" 
                           name="vehicle_image" 
                           accept="image/*" 
                           onchange="previewImage(this)"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <div class="image-preview mt-4" id="image_preview">
                <div class="no-image">
                  <span>📷</span>
                  <p>No image selected</p>
                </div>
              </div>
                    <div class="image-options mt-2">
                        <button type="button" class="btn btn-secondary" onclick="selectDefaultImage()">Use Default Image</button>
                        <button type="button" class="btn btn-secondary" onclick="clearImage()">Clear Image</button>
              </div>
            </div>
          </div>

            <div class="flex items-center">
                <input type="checkbox" 
                       name="is_active" 
                       id="is_active"
                       checked
                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="is_active" class="ml-3 text-sm font-medium text-gray-700">
                    Vehicle is active and available
            </label>
          </div>

            <div class="flex justify-end space-x-4">
            <button type="button" id="reset-form" class="btn btn-secondary">Reset</button>
                <button type="submit" class="btn btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Create Vehicle
                </button>
          </div>
        </form>
      </div>

    <!-- Fleet Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="modern-card p-6 text-center">
            <div class="text-3xl font-bold text-blue-600" id="total-vehicles">-</div>
            <div class="text-sm text-gray-600">Total Vehicles</div>
        </div>
        <div class="modern-card p-6 text-center">
            <div class="text-3xl font-bold text-green-600" id="active-vehicles">-</div>
            <div class="text-sm text-gray-600">Active Vehicles</div>
        </div>
        <div class="modern-card p-6 text-center">
            <div class="text-3xl font-bold text-yellow-600" id="assigned-vehicles">-</div>
            <div class="text-sm text-gray-600">Assigned Vehicles</div>
        </div>
        <div class="modern-card p-6 text-center">
            <div class="text-3xl font-bold text-purple-600" id="total-bookings">-</div>
            <div class="text-sm text-gray-600">Total Bookings</div>
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
                Current Fleet
            </h2>
            <div class="flex space-x-2">
                <button id="refresh-vehicles" class="fleet-btn fleet-btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>
        
               <div id="vehicle-list-container" class="vehicle-grid-modern">
            <!-- Vehicles will be loaded here by JS -->
        </div>
        <div id="no-vehicles-message" class="text-center py-12 hidden">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No vehicles found</h3>
            <p class="mt-1 text-sm text-gray-500">Add your first vehicle to the fleet.</p>
        </div>

    </div>

    <script>
// Vehicle Management System - Enhanced JavaScript
      let vehicles = [];
      let editingId = null;
      const userRole = '{{ auth()->user()->role ?? 'guest' }}';

      document.addEventListener('DOMContentLoaded', function() {
        initializeVehicleManagement();
      });

      function initializeVehicleManagement() {
        bindFormEvents();
        loadVehicles();
    updateFleetStatistics();
      }

      function bindFormEvents() {
        const toggleBtn = document.getElementById('toggle-form');
        const formWrapper = document.getElementById('vehicle-form-wrapper');
        const form = document.getElementById('vehicle-form');
        const resetBtn = document.getElementById('reset-form');
        const refreshBtn = document.getElementById('refresh-vehicles');

        // Toggle form visibility (only for admins)
        if (toggleBtn && formWrapper) {
            toggleBtn.addEventListener('click', function() {
              if (formWrapper.style.display === 'none' || formWrapper.style.display === '') {
                formWrapper.style.display = 'block';
                resetForm();
              } else {
                formWrapper.style.display = 'none';
              }
            });
        }

        // Reset form (only if reset button exists)
        if (resetBtn && formWrapper) {
            resetBtn.addEventListener('click', function() {
              formWrapper.style.display = 'none';
              resetForm();
            });
        }

        // Form submission (only for admins)
        if (form) {
            form.addEventListener('submit', function(e) {
              e.preventDefault();
              submitVehicle();
            });
        }

        // Refresh vehicles
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
              loadVehicles();
              updateFleetStatistics();
            });
        }
      }

      function resetForm() {
        editingId = null;
        const form = document.getElementById('vehicle-form');
        form.reset();
        document.querySelector('input[name="is_active"]').checked = true;
        document.getElementById('form-title').textContent = 'Add New Vehicle';
    clearImage();
      }

      function submitVehicle() {
        const form = document.getElementById('vehicle-form');
        const formData = new FormData(form);
        
        const vehicleData = {
          vehicle_number: formData.get('vehicle_number'),
          driver_name: formData.get('driver_name'),
          vehicle_type: formData.get('vehicle_type'),
          phone: formData.get('phone'),
          is_active: formData.get('is_active') === 'on'
        };

        // Handle image data
        const imageFile = formData.get('vehicle_image');
        if (imageFile && imageFile.size > 0) {
          vehicleData.image = imageFile;
        }

        if (editingId) {
          updateVehicle(editingId, vehicleData);
        } else {
          createVehicle(vehicleData);
        }
      }

      function createVehicle(data) {
        const formData = new FormData();
        
        Object.keys(data).forEach(key => {
          if (key === 'image' && data[key] instanceof File) {
            formData.append('vehicle_image', data[key]);
          } else {
            formData.append(key, data[key]);
          }
        });

        fetch('/api/v1/vehicles', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          },
          body: formData
        })
    .then(response => response.json())
        .then(result => {
          if (result.success) {
            showMessage('Vehicle created successfully!', 'success');
            loadVehicles();
            updateFleetStatistics();
            resetForm();
            document.getElementById('vehicle-form-wrapper').style.display = 'none';
          } else {
            showMessage('Failed to create vehicle: ' + (result.message || 'Unknown error'), 'error');
          }
        })
        .catch(error => {
          console.error('Error creating vehicle:', error);
          showMessage('Error creating vehicle. Please try again.', 'error');
        });
      }

      function updateVehicle(id, data) {
        const formData = new FormData();
        
        Object.keys(data).forEach(key => {
          if (key === 'image' && data[key] instanceof File) {
            formData.append('vehicle_image', data[key]);
          } else {
            formData.append(key, data[key]);
          }
        });

        fetch(`/api/v1/vehicles/${id}/update`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          },
          body: formData
        })
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            showMessage('Vehicle updated successfully!', 'success');
            loadVehicles();
            updateFleetStatistics();
            resetForm();
            document.getElementById('vehicle-form-wrapper').style.display = 'none';
          } else {
            showMessage('Failed to update vehicle: ' + (result.message || 'Unknown error'), 'error');
          }
        })
        .catch(error => {
          console.error('Error updating vehicle:', error);
          showMessage('Error updating vehicle. Please try again.', 'error');
        });
      }

      function deleteVehicle(id) {
        if (!confirm('Are you sure you want to delete this vehicle?')) {
          return;
        }

        fetch(`/api/v1/vehicles/${id}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            showMessage('Vehicle deleted successfully!', 'success');
            loadVehicles();
            updateFleetStatistics();
          } else {
            showMessage('Failed to delete vehicle: ' + (result.message || 'Unknown error'), 'error');
          }
        })
        .catch(error => {
          console.error('Error deleting vehicle:', error);
          showMessage('Error deleting vehicle. Please try again.', 'error');
        });
      }

      function loadVehicles() {
        fetch('/api/v1/vehicles')
          .then(response => response.json())
          .then(result => {
            if (result.success) {
              vehicles = result.data;
              renderVehicleCards();
            } else {
              showMessage('Failed to load vehicles', 'error');
              vehicles = [];
              renderVehicleCards();
            }
          })
          .catch(error => {
            console.error('Error loading vehicles:', error);
            showMessage('Error loading vehicles', 'error');
            vehicles = [];
            renderVehicleCards();
          });
      }

      function renderVehicleCards() {
        const container = document.getElementById('vehicle-list-container');
        container.innerHTML = '';
        const noVehiclesMessage = document.getElementById('no-vehicles-message');

        if (!vehicles || vehicles.length === 0) {
            noVehiclesMessage.classList.remove('hidden');
            return;
        } else {
            noVehiclesMessage.classList.add('hidden');
        }

        vehicles.forEach(vehicle => {
            const card = document.createElement('div');
            card.className = 'vehicle-card-modern animate-fade-in-scale';
            card.innerHTML = `
                <div class="vehicle-card-modern-header">
                    ${vehicle.image_url ? 
                        `<img src="${vehicle.image_url}" alt="${vehicle.vehicle_number}" class="vehicle-image-modern" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">` :
                        ''
                    }
                    <div class="vehicle-image-modern-placeholder" style="display: ${vehicle.image_url ? 'none' : 'flex'}">
                        ${getVehicleIcon(vehicle.vehicle_type)}
                    </div>
                    <div class="vehicle-info-modern">
                        <h3>${vehicle.vehicle_number}</h3>
                        <p>${vehicle.driver_name || 'No driver assigned'}</p>
                    </div>
                </div>
                
                <div class="vehicle-details-modern">
                    <div class="vehicle-detail-modern">
                        <svg class="vehicle-detail-modern-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>${vehicle.vehicle_type}</span>
                    </div>
                    <div class="vehicle-detail-modern">
                        <svg class="vehicle-detail-modern-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>${vehicle.phone || 'No phone'}</span>
                    </div>
                    <div class="vehicle-detail-modern">
                        <svg class="vehicle-detail-modern-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        </svg>
                        <span>${vehicle.latest_location ? 'Tracked' : 'No location'}</span>
                    </div>
                    <div class="vehicle-detail-modern">
                        <svg class="vehicle-detail-modern-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>${vehicle.latest_location ? new Date(vehicle.latest_location.recorded_at).toLocaleTimeString() : 'N/A'}</span>
                    </div>
                </div>
                
                <div class="vehicle-status-modern ${vehicle.is_active ? 'vehicle-status-modern-active' : 'vehicle-status-modern-inactive'}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    ${vehicle.is_active ? 'Active' : 'Inactive'}
                </div>
                
                ${userRole === 'admin' ? `
                <div class="vehicle-actions-modern">
                    <button onclick="editVehicle(${vehicle.id})" class="vehicle-action-btn-modern vehicle-action-btn-modern-primary">
                        Edit
                    </button>
                    <button onclick="deleteVehicle(${vehicle.id})" class="vehicle-action-btn-modern vehicle-action-btn-modern-secondary">
                        Delete
                    </button>
                </div>
                ` : ''}
            `;
            container.appendChild(card);
        });
      }

      function editVehicle(id) {
        const vehicle = vehicles.find(v => v.id === id);
        if (!vehicle) return;

        editingId = id;
        const form = document.getElementById('vehicle-form');
        form.vehicle_number.value = vehicle.vehicle_number || '';
        form.driver_name.value = vehicle.driver_name || '';
        form.vehicle_type.value = vehicle.vehicle_type || '';
        form.phone.value = vehicle.phone || '';
        form.is_active.checked = vehicle.is_active;

        document.getElementById('form-title').textContent = 'Edit Vehicle';
        document.getElementById('vehicle-form-wrapper').style.display = 'block';
      }

function updateFleetStatistics() {
    const totalVehicles = vehicles.length;
    const activeVehicles = vehicles.filter(v => v.is_active).length;
    const assignedVehicles = vehicles.filter(v => v.chauffeur_id).length;
    const totalBookings = vehicles.reduce((sum, v) => sum + (v.bookings_count || 0), 0);

    document.getElementById('total-vehicles').textContent = totalVehicles;
    document.getElementById('active-vehicles').textContent = activeVehicles;
    document.getElementById('assigned-vehicles').textContent = assignedVehicles;
    document.getElementById('total-bookings').textContent = totalBookings;
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

function getVehicleImageHtml(vehicle) {
    if (vehicle.image_url) {
        return `<img src="${vehicle.image_url}" alt="${vehicle.vehicle_number}" class="h-12 w-12 rounded-lg object-cover">`;
    } else {
        const icon = getVehicleIcon(vehicle.vehicle_type);
        return `<div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center"><span class="text-lg">${icon}</span></div>`;
    }
}

function getVehicleLocationHtml(vehicle) {
    if (vehicle.latest_location && vehicle.latest_location.latitude && vehicle.latest_location.longitude) {
        const lat = parseFloat(vehicle.latest_location.latitude).toFixed(4);
        const lng = parseFloat(vehicle.latest_location.longitude).toFixed(4);
        const speed = vehicle.latest_location.speed || 0;
        
        return `
            <div class="text-xs">
                <div class="font-mono">${lat}, ${lng}</div>
                <div class="text-gray-500">${speed} km/h</div>
            </div>
        `;
    } else {
        return '<span class="text-gray-400">No location</span>';
    }
}

      // Image handling functions
      function previewImage(input) {
        const preview = document.getElementById('image_preview');
        
        if (input.files && input.files[0]) {
          const reader = new FileReader();
          
          reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Vehicle Preview" class="max-w-full h-48 object-cover rounded-lg">`;
          };
          
          reader.readAsDataURL(input.files[0]);
        } else {
          preview.innerHTML = `
            <div class="no-image text-center py-8 text-gray-400">
                <span class="text-4xl block mb-2">📷</span>
              <p>No image selected</p>
            </div>
          `;
        }
      }

      function selectDefaultImage() {
        const vehicleType = document.querySelector('select[name="vehicle_type"]').value;
        const preview = document.getElementById('image_preview');
        
        if (vehicleType) {
          const icon = getVehicleIcon(vehicleType);
          preview.innerHTML = `
            <div class="no-image text-center py-8 text-gray-400">
                <span class="text-6xl block mb-2">${icon}</span>
              <p>Default ${vehicleType} Image</p>
            </div>
          `;
        } else {
          alert('Please select a vehicle type first');
        }
      }

      function clearImage() {
        const preview = document.getElementById('image_preview');
        const fileInput = document.getElementById('vehicle_image');
        
        preview.innerHTML = `
        <div class="no-image text-center py-8 text-gray-400">
            <span class="text-4xl block mb-2">📷</span>
            <p>No image selected</p>
          </div>
        `;
        fileInput.value = '';
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
      .image-upload-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
      }
      
      .image-preview {
        width: 100%;
    min-height: 200px;
    border: 2px dashed #e5e7eb;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    background: #f9fafb;
        position: relative;
        overflow: hidden;
      }
      
      .image-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
        border-radius: 8px;
      }
      
      .image-options {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
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

.modern-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
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