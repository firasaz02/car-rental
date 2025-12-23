<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Client\ClientBookingController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RoleSelectionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Test route to verify everything is working
Route::get('/test-route', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Routes are working correctly!',
        'timestamp' => now(),
        'user_count' => \App\Models\User::count(),
        'vehicle_count' => \App\Models\Vehicle::count(),
        'booking_count' => \App\Models\Booking::count(),
    ]);
});

// ═══════════════════════════════════════════════════════════════
// PUBLIC ROUTES
// ═══════════════════════════════════════════════════════════════

// Welcome page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Role selection page
Route::get('/role-selection', [RoleSelectionController::class, 'index'])->name('role-selection');
Route::post('/role-selection/select', [RoleSelectionController::class, 'selectRole'])->name('role-selection.select');
Route::post('/role-selection/login', [RoleSelectionController::class, 'login'])->name('role-selection.login');
Route::post('/role-selection/register', [RoleSelectionController::class, 'register'])->name('role-selection.register');

// Login redirect
Route::get('/login', function () {
    return redirect('/role-selection');
})->name('login');

// Logout route
Route::post('/logout', function () {
    try {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/')->with('success', 'You have been logged out successfully.');
    } catch (\Exception $e) {
        \Log::error('Logout error: ' . $e->getMessage());
        return redirect('/')->with('error', 'There was an issue logging out. Please try again.');
    }
})->name('logout');

// ═══════════════════════════════════════════════════════════════
// ADMIN ROUTES - Always enabled for proper functionality
// ═══════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    
    // Vehicle management
    Route::get('/vehicles', [AdminController::class, 'vehicles'])->name('vehicles');
    Route::get('/vehicles/create', [AdminController::class, 'createVehicle'])->name('vehicles.create');
    Route::post('/vehicles', [AdminController::class, 'storeVehicle'])->name('vehicles.store');
    Route::get('/vehicles/{vehicle}', [AdminController::class, 'showVehicle'])->name('vehicles.show');
    Route::get('/vehicles/{vehicle}/edit', [AdminController::class, 'editVehicle'])->name('vehicles.edit');
    Route::put('/vehicles/{vehicle}', [AdminController::class, 'updateVehicle'])->name('vehicles.update');
    Route::delete('/vehicles/{vehicle}', [AdminController::class, 'deleteVehicle'])->name('vehicles.delete');
    Route::post('/vehicles/{vehicle}/assign-chauffeur', [AdminController::class, 'assignChauffeur'])->name('vehicles.assign-chauffeur');
    Route::post('/vehicles/{vehicle}/remove-chauffeur', [AdminController::class, 'removeChauffeur'])->name('vehicles.remove-chauffeur');
    
    // Booking management
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::put('/bookings/{booking}/status', [AdminController::class, 'updateBookingStatus'])->name('bookings.status');
    
    // Fleet analytics
    Route::get('/fleet-analytics', [AdminController::class, 'fleetAnalytics'])->name('fleet-analytics');
    
    // Admin Cart Management
    Route::get('/fleet-cart-management', function () {
        return view('admin.fleet-cart-management');
    })->name('fleet-cart-management');
    
    // Map Dashboard (Admin only)
    Route::get('/map-dashboard', function () {
        return view('admin.map-dashboard');
    })->name('map-dashboard');
});

// ═══════════════════════════════════════════════════════════════
// CLIENT ROUTES - Main user functionality
// ═══════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:user'])
    ->prefix('client')
    ->name('client.')
    ->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
    
    // Rent Vehicle
    Route::get('/rent', [ClientBookingController::class, 'index'])->name('rent.index');
    Route::post('/rent', [ClientBookingController::class, 'store'])->name('rent.store');
    Route::post('/rent/check', [ClientBookingController::class, 'checkAvailability'])->name('rent.check');
    
    // My Bookings
    Route::get('/my-bookings', [ClientBookingController::class, 'myBookings'])->name('bookings.mine');
    Route::post('/my-bookings/{booking}/cancel', [ClientBookingController::class, 'cancel'])->name('bookings.cancel');
    
    // Fleet Cart System
    Route::get('/fleet-cart', function () {
        return view('fleet-cart');
    })->name('fleet-cart');
    
    // Fleet/Vehicles View (Read-only for users)
    Route::get('/fleet', function () {
        return view('vehicle-management');
    })->name('fleet');
    
    Route::get('/vehicles', function () {
        return view('vehicle-management');
    })->name('vehicles');
});

// ═══════════════════════════════════════════════════════════════
// CHAUFFEUR ROUTES - Driver functionality
// ═══════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:chauffeur'])->group(function () {
    // Dashboard
    Route::get('/chauffeur/dashboard', [UserController::class, 'dashboard'])->name('chauffeur.dashboard');
    
    // Assignments
    Route::get('/assignments', function () {
        return view('chauffeur.assignments');
    })->name('chauffeur.assignments');
    
    // Vehicle management
    Route::get('/vehicles', function () {
        $vehicles = \App\Models\Vehicle::where('is_active', true)->get();
        return view('chauffeur.vehicles', compact('vehicles'));
    })->name('chauffeur.vehicles');
});

// ═══════════════════════════════════════════════════════════════
// UNIVERSAL DASHBOARD ROUTE - Redirects based on user role
// ═══════════════════════════════════════════════════════════════
Route::middleware('auth')->get('/dashboard', function () {
    $user = auth()->user();
    
    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'user':
            return redirect()->route('client.dashboard');
        case 'chauffeur':
            return redirect()->route('chauffeur.dashboard');
        default:
            return redirect('/')->with('error', 'Invalid user role');
    }
})->name('dashboard');

// ═══════════════════════════════════════════════════════════════
// SHARED AUTHENTICATED ROUTES
// ═══════════════════════════════════════════════════════════════
Route::middleware('auth')->group(function () {
    // Profile management
    Route::get('/profile', [App\Http\Controllers\UserProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\UserProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-last-login', [App\Http\Controllers\UserProfileController::class, 'updateLastLogin'])->name('profile.update-last-login');
    
    // Map page
    Route::get('/map', function () {
        return view('map');
    })->name('map');
    
    // Reports page
    Route::get('/reports', function () {
        return view('reports');
    })->name('reports');
    
    // Settings page
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');
    
    // About page
    Route::get('/about', function () {
        return view('about');
    })->name('about');
    
    // Contact page
    Route::get('/contact', function () {
        return view('contact');
    })->name('contact');
    
    // Legacy user routes for backward compatibility
    Route::get('/user/bookings', [ClientBookingController::class, 'myBookings'])->name('user.bookings');
    Route::post('/user/bookings/{booking}/cancel', [ClientBookingController::class, 'cancel'])->name('user.bookings.cancel');
    Route::get('/user/using-car', [BookingController::class, 'usingCar'])->name('user.using-car');
    Route::post('/user/bookings/{booking}/start', [BookingController::class, 'startBooking'])->name('user.bookings.start');
    Route::post('/user/bookings/{booking}/complete', [BookingController::class, 'completeBooking'])->name('user.bookings.complete');
    Route::post('/user/bookings/{booking}/report-issue', [BookingController::class, 'reportIssue'])->name('user.bookings.report-issue');
    
    // Legacy rent routes for backward compatibility
    Route::get('/rent', [ClientBookingController::class, 'index'])->name('rent.index');
    Route::post('/rent', [ClientBookingController::class, 'store'])->name('rent.store');
    Route::post('/rent/check', [ClientBookingController::class, 'checkAvailability'])->name('rent.check');
});

// ═══════════════════════════════════════════════════════════════
// CART SYSTEM ROUTES (for all authenticated users)
// ═══════════════════════════════════════════════════════════════
Route::middleware('auth')->group(function () {
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'getUserCart'])->name('cart.get');
    Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/remove', [App\Http\Controllers\CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/confirm', [App\Http\Controllers\CartController::class, 'confirmCartItem'])->name('cart.confirm');
    Route::post('/cart/clear', [App\Http\Controllers\CartController::class, 'clearCart'])->name('cart.clear');
    Route::get('/cart/stats', [App\Http\Controllers\CartController::class, 'getCartStats'])->name('cart.stats');
});

// ═══════════════════════════════════════════════════════════════
// BOOKING ROUTES (Resource Controller)
// ═══════════════════════════════════════════════════════════════
Route::resource('bookings', BookingController::class);

// ═══════════════════════════════════════════════════════════════
// TEST ROUTES (Development only)
// ═══════════════════════════════════════════════════════════════

// Test registration route
Route::get('/test-registration', function () {
    return view('test-registration');
});
if (env('APP_DEBUG', false)) {
    Route::get('/test-register', function () {
        return view('test-register');
    });
    Route::post('/test-register', function (Request $request) {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:admin,user,chauffeur'
            ]);
            $user = \App\Models\User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role' => $validated['role'],
                'email_verified_at' => now(),
            ]);
            return response()->json([
                'success' => true,
                'message' => 'User created successfully!',
                'user_id' => $user->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    });
}