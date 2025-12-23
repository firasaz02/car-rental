@extends('layouts.admin')
@section('title', 'Vehicle Management')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Sidebar - Vehicle List -->
    <div class="lg:col-span-1">
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Vehicle List</h2>
                <a href="{{ route('admin.vehicles.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-1 px-3 rounded">
                    + Add Vehicle
                </a>
            </div>
            
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($vehicles as $vehicle)
                    <div class="border rounded-lg p-3 hover:bg-gray-50 transition duration-200 cursor-pointer vehicle-card" 
                         data-vehicle-id="{{ $vehicle->id }}">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <img class="h-12 w-12 rounded-lg object-cover" 
                                     src="{{ $vehicle->image_url }}" 
                                     alt="{{ $vehicle->vehicle_number }}"
                                     onerror="this.src='/images/default-vehicle.svg'">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900 truncate">
                                    {{ $vehicle->vehicle_number }}
                                </div>
                                <div class="text-xs text-gray-500 truncate">
                                    {{ $vehicle->driver_name }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $vehicle->vehicle_type }}
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $vehicle->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $vehicle->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-8">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p>No vehicles found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Side - Vehicle Details -->
    <div class="lg:col-span-2">
        <div class="bg-white shadow rounded-lg p-6">
            <div id="vehicle-details" class="hidden">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Vehicle Details</h2>
                    <div class="flex space-x-2">
                        <button id="edit-vehicle" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded">
                            Edit Vehicle
                        </button>
                        <button id="delete-vehicle" class="bg-red-600 hover:bg-red-700 text-white text-sm font-bold py-2 px-4 rounded">
                            Delete Vehicle
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Vehicle Image -->
                    <div>
                        <img id="vehicle-image" class="w-full h-48 object-cover rounded-lg" src="" alt="">
                    </div>

                    <!-- Vehicle Information -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Vehicle Number</label>
                            <p id="vehicle-number" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Driver Name</label>
                            <p id="driver-name" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Vehicle Type</label>
                            <p id="vehicle-type" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <p id="vehicle-phone" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span id="vehicle-status" class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full"></span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Total Bookings</label>
                            <p id="vehicle-bookings" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                    </div>
                </div>

                <!-- Chauffeur Assignment -->
                <div class="mt-6 border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Chauffeur Assignment</h3>
                    <div class="flex items-center space-x-4">
                        <div class="flex-1">
                            <label for="chauffeur-select" class="block text-sm font-medium text-gray-700 mb-2">Assign Chauffeur</label>
                            <select id="chauffeur-select" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select a chauffeur...</option>
                                @foreach(\App\Models\User::where('role', 'chauffeur')->get() as $chauffeur)
                                    <option value="{{ $chauffeur->id }}">{{ $chauffeur->name }} ({{ $chauffeur->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-shrink-0">
                            <button id="assign-chauffeur" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Assign
                            </button>
                        </div>
                    </div>
                    <div id="current-chauffeur" class="mt-3 hidden">
                        <p class="text-sm text-gray-600">Current chauffeur: <span id="chauffeur-name" class="font-medium"></span></p>
                        <button id="remove-chauffeur" class="mt-2 text-red-600 hover:text-red-800 text-sm">Remove chauffeur</button>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Select a Vehicle</h3>
                <p class="text-gray-500">Choose a vehicle from the list to view its details and manage chauffeur assignments.</p>
            </div>
        </div>
    </div>
</div>

<script>
let selectedVehicleId = null;
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Vehicle selection
document.querySelectorAll('.vehicle-card').forEach(card => {
    card.addEventListener('click', function() {
        selectedVehicleId = this.dataset.vehicleId;
        loadVehicleDetails(selectedVehicleId);
    });
});

// Load vehicle details
function loadVehicleDetails(vehicleId) {
    fetch(`/admin/vehicles/${vehicleId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('vehicle-details').classList.remove('hidden');
            document.getElementById('empty-state').classList.add('hidden');
            
            // Update vehicle details
            document.getElementById('vehicle-image').src = data.image_url;
            document.getElementById('vehicle-number').textContent = data.vehicle_number;
            document.getElementById('driver-name').textContent = data.driver_name;
            document.getElementById('vehicle-type').textContent = data.vehicle_type;
            document.getElementById('vehicle-phone').textContent = data.phone || 'N/A';
            document.getElementById('vehicle-bookings').textContent = data.bookings_count;
            
            // Update status
            const statusElement = document.getElementById('vehicle-status');
            statusElement.textContent = data.is_active ? 'Active' : 'Inactive';
            statusElement.className = `mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full ${data.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;
            
            // Update chauffeur assignment
            if (data.chauffeur) {
                document.getElementById('chauffeur-select').value = data.chauffeur.id;
                document.getElementById('chauffeur-name').textContent = data.chauffeur.name;
                document.getElementById('current-chauffeur').classList.remove('hidden');
            } else {
                document.getElementById('chauffeur-select').value = '';
                document.getElementById('current-chauffeur').classList.add('hidden');
            }
            
            // Update edit/delete buttons
            document.getElementById('edit-vehicle').onclick = () => window.location.href = `/admin/vehicles/${vehicleId}/edit`;
            document.getElementById('delete-vehicle').onclick = () => deleteVehicle(vehicleId);
        })
        .catch(error => {
            console.error('Error loading vehicle details:', error);
        });
}

// Assign chauffeur
document.getElementById('assign-chauffeur').addEventListener('click', function() {
    const chauffeurId = document.getElementById('chauffeur-select').value;
    
    if (!chauffeurId) {
        alert('Please select a chauffeur');
        return;
    }
    
    fetch(`/admin/vehicles/${selectedVehicleId}/assign-chauffeur`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ chauffeur_id: chauffeurId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Chauffeur assigned successfully!');
            loadVehicleDetails(selectedVehicleId);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error assigning chauffeur:', error);
        alert('An error occurred');
    });
});

// Remove chauffeur
document.getElementById('remove-chauffeur').addEventListener('click', function() {
    if (confirm('Are you sure you want to remove the chauffeur from this vehicle?')) {
        fetch(`/admin/vehicles/${selectedVehicleId}/remove-chauffeur`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Chauffeur removed successfully!');
                loadVehicleDetails(selectedVehicleId);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error removing chauffeur:', error);
            alert('An error occurred');
        });
    }
});

// Delete vehicle
function deleteVehicle(vehicleId) {
    if (!vehicleId) {
        alert('No vehicle selected for deletion.');
        return;
    }
    
    if (confirm('Are you sure you want to delete this vehicle? This action cannot be undone.')) {
        fetch(`/admin/vehicles/${vehicleId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.ok || response.status === 200) {
                return response.json().catch(() => ({ success: true }));
            } else {
                return response.json().then(data => {
                    throw new Error(data.message || 'Error deleting vehicle');
                });
            }
        })
        .then(data => {
            alert('Vehicle deleted successfully!');
            location.reload();
        })
        .catch(error => {
            console.error('Error deleting vehicle:', error);
            alert('Error deleting vehicle: ' + error.message);
        });
    }
}
</script>
@endsection
