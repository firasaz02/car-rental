<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\VehicleLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with optimized queries and caching
     */
    public function dashboard()
    {
        // Cache dashboard statistics for 60 seconds to improve performance
        $stats = Cache::remember('admin_dashboard_stats', 60, function () {
            return $this->getDashboardStatistics();
        });

        // Get recent activities with eager loading
        $recentActivities = $this->getRecentActivities();
        
        // Get top vehicles with booking counts
        $topVehicles = $this->getTopVehicles();

        return view('admin.dashboard', compact('stats', 'recentActivities', 'topVehicles'));
    }

    /**
     * Get comprehensive dashboard statistics with optimized queries
     */
    private function getDashboardStatistics(): array
    {
        // Use raw SQL for better performance on large datasets
        $vehicleStats = DB::select("
            SELECT 
                COUNT(*) as total_vehicles,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_vehicles,
                SUM(CASE WHEN chauffeur_id IS NOT NULL THEN 1 ELSE 0 END) as assigned_vehicles,
                SUM(CASE WHEN chauffeur_id IS NULL THEN 1 ELSE 0 END) as unassigned_vehicles
            FROM vehicles
        ")[0];

        $userStats = DB::select("
            SELECT 
                COUNT(*) as total_users,
                SUM(CASE WHEN role = 'user' THEN 1 ELSE 0 END) as regular_users,
                SUM(CASE WHEN role = 'chauffeur' THEN 1 ELSE 0 END) as chauffeurs,
                SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as admins
            FROM users
        ")[0];

        $bookingStats = DB::select("
            SELECT 
                COUNT(*) as total_bookings,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_bookings,
                SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_bookings,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_bookings
            FROM bookings
        ")[0];

        // Weekly statistics using raw SQL for better performance
        $weeklyStats = $this->getWeeklyStatistics();

        return [
            'vehicles' => $vehicleStats,
            'users' => $userStats,
            'bookings' => $bookingStats,
            'weekly' => $weeklyStats
        ];
    }

    /**
     * Get weekly statistics with optimized queries
     */
    private function getWeeklyStatistics(): array
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $weeklyBookings = DB::select("
            SELECT 
                COUNT(*) as total_bookings,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_bookings,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_bookings
            FROM bookings 
            WHERE created_at BETWEEN ? AND ?
        ", [$startOfWeek, $endOfWeek])[0];

        $weeklyRevenue = DB::select("
            SELECT 
                COALESCE(SUM(total_amount), 0) as total_revenue
            FROM bookings 
            WHERE status = 'completed' 
            AND created_at BETWEEN ? AND ?
        ", [$startOfWeek, $endOfWeek])[0];

        return [
            'bookings' => $weeklyBookings,
            'revenue' => $weeklyRevenue
        ];
    }

    /**
     * Get recent activities with eager loading to prevent N+1 queries
     */
    private function getRecentActivities(): array
    {
        return [
            'recent_bookings' => Booking::with(['user:id,name,email', 'vehicle:id,vehicle_number,driver_name'])
                ->latest()
                ->limit(10)
                ->get(),
            
            'recent_users' => User::select('id', 'name', 'email', 'role', 'created_at')
                ->latest()
                ->limit(5)
                ->get(),
            
            'recent_vehicles' => Vehicle::with(['chauffeur:id,name'])
                ->latest()
                ->limit(5)
                ->get()
        ];
    }

    /**
     * Get top vehicles by booking count with optimized query
     */
    private function getTopVehicles()
    {
        return Vehicle::withCount('bookings')
            ->with(['chauffeur:id,name'])
            ->orderBy('bookings_count', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get fleet analytics for API endpoints
     */
    public function fleetAnalytics(): JsonResponse
    {
        $analytics = Cache::remember('fleet_analytics', 60, function () {
            return [
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
        });

        return response()->json($analytics);
    }

    /**
     * Get user management data with optimized queries
     */
    public function users()
    {
        $users = User::with(['vehicles:id,vehicle_number,driver_id'])
            ->select('id', 'name', 'email', 'role', 'created_at', 'updated_at')
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    /**
     * Get vehicle management data with optimized queries
     */
    public function vehicles()
    {
        $vehicles = Vehicle::with(['chauffeur:id,name,email', 'bookings:id,vehicle_id,status'])
            ->select('id', 'vehicle_number', 'driver_name', 'vehicle_type', 'phone', 'is_active', 'chauffeur_id', 'created_at')
            ->paginate(20);

        return view('admin.vehicles', compact('vehicles'));
    }

    /**
     * Get booking management data with optimized queries
     */
    public function bookings()
    {
        $bookings = Booking::with(['user:id,name,email', 'vehicle:id,vehicle_number,driver_name'])
            ->select('id', 'user_id', 'vehicle_id', 'start_date', 'end_date', 'status', 'total_amount', 'created_at')
            ->latest()
            ->paginate(20);

        return view('admin.bookings', compact('bookings'));
    }

    /**
     * Create a new user
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Store a new user
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user,chauffeur'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'], // User model handles hashing automatically
            'role' => $validated['role'],
            'email_verified_at' => now(),
        ]);

        // Clear cache after creating user
        Cache::forget('admin_dashboard_stats');

        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    /**
     * Edit user
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user,chauffeur'
        ]);

        $user->update($validated);

        // Clear cache after updating user
        Cache::forget('admin_dashboard_stats');

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    /**
     * Delete user
     */
    public function deleteUser(User $user)
    {
        $user->delete();

        // Clear cache after deleting user
        Cache::forget('admin_dashboard_stats');

        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }

    /**
     * Create a new vehicle
     */
    public function createVehicle()
    {
        $chauffeurs = User::where('role', 'chauffeur')
            ->select('id', 'name')
            ->get();

        return view('admin.vehicles.create', compact('chauffeurs'));
    }

    /**
     * Store a new vehicle
     */
    public function storeVehicle(Request $request)
    {
        $validated = $request->validate([
            'vehicle_number' => 'required|string|max:255|unique:vehicles',
            'driver_name' => 'nullable|string|max:255',
            'vehicle_type' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'chauffeur_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean'
        ]);

        $vehicle = Vehicle::create($validated);

        // Clear cache after creating vehicle
        Cache::forget('admin_dashboard_stats');
        Cache::forget('fleet_analytics');

        return redirect()->route('admin.vehicles')->with('success', 'Vehicle created successfully!');
    }

    /**
     * Show vehicle details
     */
    public function showVehicle(Vehicle $vehicle)
    {
        $vehicle->load(['chauffeur', 'bookings.user']);
        
        return view('admin.vehicles.show', compact('vehicle'));
    }

    /**
     * Edit vehicle
     */
    public function editVehicle(Vehicle $vehicle)
    {
        $chauffeurs = User::where('role', 'chauffeur')
            ->select('id', 'name')
            ->get();

        return view('admin.vehicles.edit', compact('vehicle', 'chauffeurs'));
    }

    /**
     * Update vehicle
     */
    public function updateVehicle(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'vehicle_number' => 'required|string|max:255|unique:vehicles,vehicle_number,' . $vehicle->id,
            'driver_name' => 'nullable|string|max:255',
            'vehicle_type' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'chauffeur_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean'
        ]);

        $vehicle->update($validated);

        // Clear cache after updating vehicle
        Cache::forget('admin_dashboard_stats');
        Cache::forget('fleet_analytics');

        return redirect()->route('admin.vehicles')->with('success', 'Vehicle updated successfully!');
    }

    /**
     * Delete vehicle
     */
    public function deleteVehicle(Vehicle $vehicle)
    {
        $vehicle->delete();

        // Clear cache after deleting vehicle
        Cache::forget('admin_dashboard_stats');
        Cache::forget('fleet_analytics');

        return redirect()->route('admin.vehicles')->with('success', 'Vehicle deleted successfully!');
    }

    /**
     * Assign chauffeur to vehicle
     */
    public function assignChauffeur(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'chauffeur_id' => 'required|exists:users,id'
        ]);

        $chauffeur = User::find($validated['chauffeur_id']);
        if ($chauffeur->role !== 'chauffeur') {
            return response()->json([
                'success' => false,
                'message' => 'Selected user is not a chauffeur'
            ], 400);
        }

        $vehicle->update(['chauffeur_id' => $validated['chauffeur_id']]);

        // Clear cache after assignment
        Cache::forget('admin_dashboard_stats');
        Cache::forget('fleet_analytics');

        return response()->json([
            'success' => true,
            'message' => 'Chauffeur assigned successfully'
        ]);
    }

    /**
     * Remove chauffeur from vehicle
     */
    public function removeChauffeur(Vehicle $vehicle)
    {
        $vehicle->update(['chauffeur_id' => null]);

        // Clear cache after removal
        Cache::forget('admin_dashboard_stats');
        Cache::forget('fleet_analytics');

        return response()->json([
            'success' => true,
            'message' => 'Chauffeur removed successfully'
        ]);
    }

    /**
     * Update booking status
     */
    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        $booking->update(['status' => $validated['status']]);

        // Clear cache after status update
        Cache::forget('admin_dashboard_stats');
        Cache::forget('fleet_analytics');

        return response()->json([
            'success' => true,
            'message' => 'Booking status updated successfully'
        ]);
    }

    /**
     * Clear all caches (admin utility)
     */
    public function clearCaches()
    {
        Cache::flush();
        
        return response()->json([
            'success' => true,
            'message' => 'All caches cleared successfully'
        ]);
    }
}
