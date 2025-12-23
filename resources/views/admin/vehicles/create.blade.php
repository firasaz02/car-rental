@extends('layouts.admin')
@section('title', 'Create Vehicle')

@section('content')
<div class="max-w-4xl mx-auto p-6 space-y-6 fade-in-up">
    <!-- Header -->
    <div class="modern-card p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-green-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Add New Vehicle</h1>
                <p class="text-gray-600">Add a new vehicle to your fleet with comprehensive information</p>
            </div>
            <a href="{{ route('admin.vehicles') }}" class="btn btn-secondary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Vehicles
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.vehicles.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Vehicle Information -->
        <div class="modern-card p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path>
                </svg>
                Vehicle Information
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="vehicle_number" class="block text-sm font-semibold text-gray-700 mb-2">Vehicle Number *</label>
                    <input type="text" 
                           name="vehicle_number" 
                           id="vehicle_number" 
                           value="{{ old('vehicle_number') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('vehicle_number') border-red-500 @enderror"
                           placeholder="e.g., TUN-1234"
                           required>
                    @error('vehicle_number')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="driver_name" class="block text-sm font-semibold text-gray-700 mb-2">Driver Name *</label>
                    <input type="text" 
                           name="driver_name" 
                           id="driver_name" 
                           value="{{ old('driver_name') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('driver_name') border-red-500 @enderror"
                           placeholder="e.g., Ahmed Ben Ali"
                           required>
                    @error('driver_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="vehicle_type" class="block text-sm font-semibold text-gray-700 mb-2">Vehicle Type *</label>
                    <select name="vehicle_type" 
                            id="vehicle_type"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('vehicle_type') border-red-500 @enderror"
                            required>
                        <option value="">Select Vehicle Type</option>
                        <option value="Sedan" {{ old('vehicle_type') === 'Sedan' ? 'selected' : '' }}>Sedan</option>
                        <option value="SUV" {{ old('vehicle_type') === 'SUV' ? 'selected' : '' }}>SUV</option>
                        <option value="Hatchback" {{ old('vehicle_type') === 'Hatchback' ? 'selected' : '' }}>Hatchback</option>
                        <option value="Van" {{ old('vehicle_type') === 'Van' ? 'selected' : '' }}>Van</option>
                        <option value="Truck" {{ old('vehicle_type') === 'Truck' ? 'selected' : '' }}>Truck</option>
                        <option value="Bus" {{ old('vehicle_type') === 'Bus' ? 'selected' : '' }}>Bus</option>
                    </select>
                    @error('vehicle_type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                    <input type="tel" 
                           name="phone" 
                           id="phone" 
                           value="{{ old('phone') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('phone') border-red-500 @enderror"
                           placeholder="+216 XX XXX XXX">
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Chauffeur Assignment -->
        <div class="modern-card p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Chauffeur Assignment
            </h2>
            
            <div>
                <label for="chauffeur_id" class="block text-sm font-semibold text-gray-700 mb-2">Assign Chauffeur</label>
                <select name="chauffeur_id" 
                        id="chauffeur_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('chauffeur_id') border-red-500 @enderror">
                    <option value="">No chauffeur assigned</option>
                    @foreach(\App\Models\User::where('role', 'chauffeur')->where('is_active', true)->get() as $chauffeur)
                        <option value="{{ $chauffeur->id }}" {{ old('chauffeur_id') == $chauffeur->id ? 'selected' : '' }}>
                            {{ $chauffeur->name }} ({{ $chauffeur->email }})
                        </option>
                    @endforeach
                </select>
                @error('chauffeur_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500">Select a chauffeur to assign to this vehicle (optional)</p>
            </div>
        </div>

        <!-- Vehicle Image -->
        <div class="modern-card p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Vehicle Image
            </h2>
            
            <div>
                <label for="image_url" class="block text-sm font-semibold text-gray-700 mb-2">Image URL</label>
                <input type="url" 
                       name="image_url" 
                       id="image_url" 
                       value="{{ old('image_url') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('image_url') border-red-500 @enderror"
                       placeholder="https://example.com/vehicle-image.jpg">
                @error('image_url')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500">Enter a URL to the vehicle image or leave empty for default</p>
            </div>
        </div>

        <!-- Vehicle Status -->
        <div class="modern-card p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                <svg class="w-6 h-6 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Vehicle Status
            </h2>
            
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <input type="checkbox" 
                           name="is_active" 
                           id="is_active" 
                           value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="is_active" class="ml-3 text-sm font-medium text-gray-700">
                        Vehicle is active and available for bookings
                    </label>
                </div>
            </div>
            @error('is_active')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.vehicles') }}" class="btn btn-secondary">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create Vehicle
            </button>
        </div>
    </form>
</div>
@endsection