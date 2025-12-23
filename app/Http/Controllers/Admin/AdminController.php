<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_vehicles' => Vehicle::count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'active_chauffeurs' => User::where('role', 'chauffeur')->where('is_active', true)->count(),
            'revenue' => Booking::where('status', 'completed')->sum('total_amount') ?? 0,
        ];

        $recent_bookings = Booking::with(['user', 'vehicle'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_bookings'));
    }

    public function users()
    {
        $users = User::withCount('bookings')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user,chauffeur',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'license_number' => 'nullable|string|max:50',
            'license_expiry' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        // Handle profile image upload
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile-images', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'], // User model handles hashing automatically
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'country' => $validated['country'] ?? null,
            'emergency_contact' => $validated['emergency_contact'] ?? null,
            'emergency_phone' => $validated['emergency_phone'] ?? null,
            'license_number' => $validated['license_number'] ?? null,
            'license_expiry' => $validated['license_expiry'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'profile_image' => $profileImagePath,
            'is_active' => $validated['is_active'] ?? true,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,user,chauffeur',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'license_number' => 'nullable|string|max:50',
            'license_expiry' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        // User model handles password hashing automatically with 'hashed' cast
        // No need to hash manually - just pass the plain password
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')->store('profile-images', 'public');
        }

        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }

    public function toggleUserStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $user->update(['is_active' => $validated['is_active']]);

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully',
            'is_active' => $user->is_active
        ]);
    }

    public function vehicles()
    {
        $vehicles = Vehicle::withCount('bookings')->paginate(10);
        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function createVehicle()
    {
        return view('admin.vehicles.create');
    }

    public function storeVehicle(Request $request)
    {
        $validated = $request->validate([
            'vehicle_number' => 'required|string|max:255|unique:vehicles',
            'driver_name' => 'required|string|max:255',
            'chauffeur_id' => 'nullable|exists:users,id',
            'vehicle_type' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'image_url' => 'nullable|string|max:255',
        ]);

        // Handle is_active checkbox
        $validated['is_active'] = $request->has('is_active');

        Vehicle::create($validated);

        return redirect()->route('admin.vehicles')->with('success', 'Vehicle created successfully!');
    }

    public function editVehicle(Vehicle $vehicle)
    {
        return view('admin.vehicles.edit', compact('vehicle'));
    }

    public function updateVehicle(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'vehicle_number' => ['required', 'string', 'max:255', Rule::unique('vehicles')->ignore($vehicle->id)],
            'driver_name' => 'required|string|max:255',
            'chauffeur_id' => 'nullable|exists:users,id',
            'vehicle_type' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'image_url' => 'nullable|string|max:255',
        ]);

        // Handle is_active checkbox
        $validated['is_active'] = $request->has('is_active');

        $vehicle->update($validated);

        return redirect()->route('admin.vehicles')->with('success', 'Vehicle updated successfully!');
    }

    public function deleteVehicle(Request $request, Vehicle $vehicle)
    {
        try {
            $vehicleNumber = $vehicle->vehicle_number;
            $vehicle->delete();
            
            if ($request->expectsJson() || $request->wantsJson() || $request->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => true,
                    'message' => 'Vehicle deleted successfully!'
                ]);
            }
            
            return redirect()->route('admin.vehicles')->with('success', 'Vehicle deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Error deleting vehicle', [
                'vehicle_id' => $vehicle->id,
                'error' => $e->getMessage()
            ]);
            
            if ($request->expectsJson() || $request->wantsJson() || $request->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete vehicle: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.vehicles')->with('error', 'Failed to delete vehicle. Please try again.');
        }
    }

    public function showVehicle(Vehicle $vehicle)
    {
        $vehicle->load(['chauffeur', 'bookings']);
        return response()->json([
            'id' => $vehicle->id,
            'vehicle_number' => $vehicle->vehicle_number,
            'driver_name' => $vehicle->driver_name,
            'vehicle_type' => $vehicle->vehicle_type,
            'phone' => $vehicle->phone,
            'is_active' => $vehicle->is_active,
            'image_url' => $vehicle->image_url,
            'bookings_count' => $vehicle->bookings_count,
            'chauffeur' => $vehicle->chauffeur ? [
                'id' => $vehicle->chauffeur->id,
                'name' => $vehicle->chauffeur->name,
                'email' => $vehicle->chauffeur->email
            ] : null
        ]);
    }

    public function assignChauffeur(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'chauffeur_id' => 'required|exists:users,id'
        ]);

        // Check if the user is actually a chauffeur
        $chauffeur = User::find($validated['chauffeur_id']);
        if ($chauffeur->role !== 'chauffeur') {
            return response()->json([
                'success' => false,
                'message' => 'Selected user is not a chauffeur'
            ], 400);
        }

        $vehicle->update(['chauffeur_id' => $validated['chauffeur_id']]);

        return response()->json([
            'success' => true,
            'message' => 'Chauffeur assigned successfully'
        ]);
    }

    public function removeChauffeur(Vehicle $vehicle)
    {
        $vehicle->update(['chauffeur_id' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Chauffeur removed successfully'
        ]);
    }

    public function bookings()
    {
        $bookings = Booking::with(['user', 'vehicle'])->latest()->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $booking->update($validated);

        return redirect()->route('admin.bookings')->with('success', 'Booking status updated successfully!');
    }

    public function fleetAnalytics()
    {
        $analytics = [
            'total_vehicles' => Vehicle::count(),
            'active_vehicles' => Vehicle::where('is_active', true)->count(),
            'assigned_vehicles' => Vehicle::whereNotNull('chauffeur_id')->count(),
            'unassigned_vehicles' => Vehicle::whereNull('chauffeur_id')->count(),
            'vehicles_by_type' => Vehicle::selectRaw('vehicle_type, COUNT(*) as count')
                ->groupBy('vehicle_type')
                ->get(),
            'recent_bookings' => Booking::with(['user', 'vehicle'])
                ->latest()
                ->limit(10)
                ->get(),
            'booking_stats' => [
                'total_bookings' => Booking::count(),
                'pending_bookings' => Booking::where('status', 'pending')->count(),
                'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
                'completed_bookings' => Booking::where('status', 'completed')->count(),
                'cancelled_bookings' => Booking::where('status', 'cancelled')->count(),
            ],
            'top_vehicles' => Vehicle::withCount('bookings')
                ->orderBy('bookings_count', 'desc')
                ->limit(5)
                ->get(),
        ];

        return response()->json($analytics);
    }
}
