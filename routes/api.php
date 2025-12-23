<?php

use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\VehicleLocationController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Vehicle routes
    Route::apiResource('vehicles', VehicleController::class);
    Route::post('vehicles/{id}/update', [VehicleController::class, 'update']); // For image uploads
    
    // Location routes
    Route::post('locations', [VehicleLocationController::class, 'store']);
    Route::get('locations/current', [VehicleLocationController::class, 'getAllCurrentLocations']);
    Route::get('vehicles/{vehicleId}/history', [VehicleLocationController::class, 'getVehicleHistory']);
    
    // Booking routes
    Route::get('bookings', [BookingController::class, 'apiIndex']);
    Route::post('bookings', [BookingController::class, 'apiStore']);
    Route::put('bookings/{booking}', [BookingController::class, 'apiUpdate']);
    Route::delete('bookings/{booking}', [BookingController::class, 'apiDestroy']);
    
    // User booking statistics
    Route::get('user-bookings-stats', [BookingController::class, 'userBookingStats']);
    
    // Cart System API Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('cart', [App\Http\Controllers\CartController::class, 'getUserCart']);
        Route::post('cart/add', [App\Http\Controllers\CartController::class, 'addToCart']);
        Route::post('cart/remove', [App\Http\Controllers\CartController::class, 'removeFromCart']);
        Route::post('cart/confirm', [App\Http\Controllers\CartController::class, 'confirmCartItem']);
        Route::post('cart/clear', [App\Http\Controllers\CartController::class, 'clearCart']);
        Route::get('cart/stats', [App\Http\Controllers\CartController::class, 'getCartStats']);
        Route::post('cart/checkout', [App\Http\Controllers\CartController::class, 'checkout']);
    });
    
    // Admin Cart Management API Routes
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::get('admin/notifications', [App\Http\Controllers\Admin\AdminCartController::class, 'getNotifications']);
        Route::get('admin/activities', [App\Http\Controllers\Admin\AdminCartController::class, 'getActivities']);
        Route::get('admin/users', [App\Http\Controllers\Admin\AdminCartController::class, 'getUsers']);
        Route::post('admin/notifications/{notificationId}/read', [App\Http\Controllers\Admin\AdminCartController::class, 'markNotificationAsRead']);
        Route::post('admin/approve-action', [App\Http\Controllers\Admin\AdminCartController::class, 'approveAction']);
        Route::post('admin/approve-activity', [App\Http\Controllers\Admin\AdminCartController::class, 'approveActivity']);
        Route::get('admin/fleet-analytics', [App\Http\Controllers\Admin\AdminCartController::class, 'getFleetAnalytics']);
        Route::get('admin/export-data', [App\Http\Controllers\Admin\AdminCartController::class, 'exportData']);
        Route::post('admin/clear-notifications', [App\Http\Controllers\Admin\AdminCartController::class, 'clearAllNotifications']);
        Route::post('admin/notify', [App\Http\Controllers\Admin\AdminCartController::class, 'notifyAdmin']);
    });
});
