<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Booking;
use App\Models\CartActivity;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Get user's cart items
     */
    public function getUserCart(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $cartItems = $request->session()->get('cart', []);
            
            return response()->json([
                'success' => true,
                'data' => $cartItems,
                'message' => 'Cart items retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting user cart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve cart items'
            ], 500);
        }
    }

    /**
     * Add vehicle to cart
     */
    public function addToCart(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'vehicle_id' => 'required|exists:vehicles,id'
            ]);

            $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
            $user = Auth::user();
            
            $cart = $request->session()->get('cart', []);
            
            // Check if vehicle is already in cart
            if (isset($cart[$validated['vehicle_id']])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle is already in your cart'
                ], 400);
            }

            // Add to cart
            $cart[$validated['vehicle_id']] = [
                'vehicle_id' => $vehicle->id,
                'vehicle_number' => $vehicle->vehicle_number,
                'driver_name' => $vehicle->driver_name,
                'vehicle_type' => $vehicle->vehicle_type,
                'phone' => $vehicle->phone,
                'is_active' => $vehicle->is_active,
                'image_url' => $vehicle->image_url,
                'added_at' => now()->toISOString(),
                'confirmed' => false
            ];

            $request->session()->put('cart', $cart);

            // Log activity
            $this->logCartActivity($user->id, 'item_added', [
                'vehicle_id' => $vehicle->id,
                'vehicle_number' => $vehicle->vehicle_number
            ]);

            // Notify admin
            $this->notifyAdmin('item_added', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'vehicle_id' => $vehicle->id,
                'vehicle_number' => $vehicle->vehicle_number
            ]);

            return response()->json([
                'success' => true,
                'data' => $cart[$validated['vehicle_id']],
                'message' => 'Vehicle added to cart successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error adding to cart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add vehicle to cart'
            ], 500);
        }
    }

    /**
     * Remove vehicle from cart
     */
    public function removeFromCart(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'vehicle_id' => 'required|exists:vehicles,id'
            ]);

            $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
            $user = Auth::user();
            
            $cart = $request->session()->get('cart', []);
            
            if (!isset($cart[$validated['vehicle_id']])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle not found in cart'
                ], 404);
            }

            unset($cart[$validated['vehicle_id']]);
            $request->session()->put('cart', $cart);

            // Log activity
            $this->logCartActivity($user->id, 'item_removed', [
                'vehicle_id' => $vehicle->id,
                'vehicle_number' => $vehicle->vehicle_number
            ]);

            // Notify admin
            $this->notifyAdmin('item_removed', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'vehicle_id' => $vehicle->id,
                'vehicle_number' => $vehicle->vehicle_number
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle removed from cart successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing from cart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove vehicle from cart'
            ], 500);
        }
    }

    /**
     * Confirm cart item
     */
    public function confirmCartItem(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'vehicle_id' => 'required|exists:vehicles,id'
            ]);

            $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
            $user = Auth::user();
            
            $cart = $request->session()->get('cart', []);
            
            if (!isset($cart[$validated['vehicle_id']])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle not found in cart'
                ], 404);
            }

            $cart[$validated['vehicle_id']]['confirmed'] = true;
            $cart[$validated['vehicle_id']]['confirmed_at'] = now()->toISOString();
            $request->session()->put('cart', $cart);

            // Log activity
            $this->logCartActivity($user->id, 'item_confirmed', [
                'vehicle_id' => $vehicle->id,
                'vehicle_number' => $vehicle->vehicle_number
            ]);

            // Notify admin
            $this->notifyAdmin('item_confirmed', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'vehicle_id' => $vehicle->id,
                'vehicle_number' => $vehicle->vehicle_number
            ]);

            return response()->json([
                'success' => true,
                'data' => $cart[$validated['vehicle_id']],
                'message' => 'Vehicle confirmed successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error confirming cart item: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm vehicle'
            ], 500);
        }
    }

    /**
     * Process checkout
     */
    public function checkout(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'items' => 'required|array',
                'user_id' => 'required|exists:users,id'
            ]);

            $user = User::findOrFail($validated['user_id']);
            $cart = $request->session()->get('cart', []);
            
            if (empty($cart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty'
                ], 400);
            }

            DB::beginTransaction();

            try {
                $bookings = [];
                
                foreach ($cart as $vehicleId => $cartItem) {
                    // Get vehicle to retrieve chauffeur_id
                    $vehicle = Vehicle::find($vehicleId);
                    
                    // Create booking for each vehicle
                    $booking = Booking::create([
                        'user_id' => $user->id,
                        'vehicle_id' => $vehicleId,
                        'chauffeur_id' => $vehicle->chauffeur_id ?? null, // Handle null chauffeur_id
                        'start_date' => now(),
                        'end_date' => now()->addDays(1),
                        'status' => 'confirmed',
                        'notes' => 'Cart checkout booking'
                    ]);
                    
                    $bookings[] = $booking;
                }

                // Clear cart
                $request->session()->forget('cart');

                // Log activity
                $this->logCartActivity($user->id, 'checkout_completed', [
                    'bookings_count' => count($bookings),
                    'vehicle_ids' => array_keys($cart)
                ]);

                // Notify admin
                $this->notifyAdmin('checkout_completed', [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'bookings_count' => count($bookings),
                    'vehicle_ids' => array_keys($cart)
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'data' => [
                        'bookings' => $bookings,
                        'total_bookings' => count($bookings)
                    ],
                    'message' => 'Checkout completed successfully'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error processing checkout: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to process checkout'
            ], 500);
        }
    }

    /**
     * Clear cart
     */
    public function clearCart(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $cart = $request->session()->get('cart', []);
            
            if (empty($cart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is already empty'
                ], 400);
            }

            $request->session()->forget('cart');

            // Log activity
            $this->logCartActivity($user->id, 'cart_cleared', [
                'items_count' => count($cart)
            ]);

            // Notify admin
            $this->notifyAdmin('cart_cleared', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'items_count' => count($cart)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error clearing cart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart'
            ], 500);
        }
    }

    /**
     * Get cart statistics
     */
    public function getCartStats(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $cart = $request->session()->get('cart', []);
            
            $stats = [
                'total_items' => count($cart),
                'confirmed_items' => count(array_filter($cart, fn($item) => $item['confirmed'])),
                'pending_items' => count(array_filter($cart, fn($item) => !$item['confirmed'])),
                'total_users' => User::where('role', 'user')->count(),
                'active_carts' => CartActivity::where('status', 'active')->count(),
                'pending_approvals' => AdminNotification::where('read', false)->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Cart statistics retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting cart stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve cart statistics'
            ], 500);
        }
    }

    /**
     * Log cart activity
     */
    private function logCartActivity(int $userId, string $type, array $data): void
    {
        CartActivity::create([
            'user_id' => $userId,
            'type' => $type,
            'data' => json_encode($data),
            'status' => 'active',
            'created_at' => now()
        ]);
    }

    /**
     * Notify admin
     */
    private function notifyAdmin(string $action, array $data): void
    {
        AdminNotification::create([
            'action' => $action,
            'data' => json_encode($data),
            'read' => false,
            'created_at' => now()
        ]);
    }
}
