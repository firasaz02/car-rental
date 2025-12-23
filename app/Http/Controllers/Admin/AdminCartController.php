<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Booking;
use App\Models\CartActivity;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminCartController extends Controller
{
    /**
     * Get all admin notifications
     */
    public function getNotifications(Request $request): JsonResponse
    {
        try {
            $notifications = AdminNotification::orderBy('created_at', 'desc')
                ->limit(100)
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'action' => $notification->action,
                        'data' => json_decode($notification->data, true),
                        'read' => $notification->read,
                        'created_at' => $notification->created_at->toISOString()
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $notifications,
                'message' => 'Notifications retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting admin notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve notifications'
            ], 500);
        }
    }

    /**
     * Get all cart activities
     */
    public function getActivities(Request $request): JsonResponse
    {
        try {
            $activities = CartActivity::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(200)
                ->get()
                ->map(function ($activity) {
                    return [
                        'id' => $activity->id,
                        'user_id' => $activity->user_id,
                        'user_name' => $activity->user->name ?? 'Unknown User',
                        'type' => $activity->type,
                        'data' => json_decode($activity->data, true),
                        'status' => $activity->status,
                        'created_at' => $activity->created_at->toISOString()
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $activities,
                'message' => 'Activities retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting cart activities: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve activities'
            ], 500);
        }
    }

    /**
     * Get all users
     */
    public function getUsers(Request $request): JsonResponse
    {
        try {
            $users = User::where('role', 'user')
                ->select('id', 'name', 'email', 'created_at')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => 'Users retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting users: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users'
            ], 500);
        }
    }

    /**
     * Mark notification as read
     */
    public function markNotificationAsRead(Request $request, int $notificationId): JsonResponse
    {
        try {
            $notification = AdminNotification::findOrFail($notificationId);
            $notification->update(['read' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking notification as read: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read'
            ], 500);
        }
    }

    /**
     * Approve cart action
     */
    public function approveAction(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'notification_id' => 'required|exists:admin_notifications,id',
                'action' => 'required|string',
                'data' => 'required|array'
            ]);

            $notification = AdminNotification::findOrFail($validated['notification_id']);
            
            DB::beginTransaction();

            try {
                // Process the approval based on action type
                switch ($validated['action']) {
                    case 'item_confirmed':
                        $this->processItemConfirmation($validated['data']);
                        break;
                    case 'checkout_completed':
                        $this->processCheckoutApproval($validated['data']);
                        break;
                    case 'item_added':
                        $this->processItemAddition($validated['data']);
                        break;
                    case 'item_removed':
                        $this->processItemRemoval($validated['data']);
                        break;
                    default:
                        throw new \Exception('Unknown action type');
                }

                // Mark notification as read
                $notification->update(['read' => true]);

                // Log admin action
                $this->logAdminAction('action_approved', [
                    'notification_id' => $notification->id,
                    'action' => $validated['action'],
                    'data' => $validated['data']
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Action approved successfully'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error approving action: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve action'
            ], 500);
        }
    }

    /**
     * Approve activity
     */
    public function approveActivity(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'activity_id' => 'required|exists:cart_activities,id'
            ]);

            $activity = CartActivity::findOrFail($validated['activity_id']);
            $activity->update(['status' => 'approved']);

            // Log admin action
            $this->logAdminAction('activity_approved', [
                'activity_id' => $activity->id,
                'user_id' => $activity->user_id,
                'type' => $activity->type
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Activity approved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error approving activity: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve activity'
            ], 500);
        }
    }

    /**
     * Get fleet analytics
     */
    public function getFleetAnalytics(Request $request): JsonResponse
    {
        try {
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
                'cart_stats' => [
                    'total_users' => User::where('role', 'user')->count(),
                    'active_carts' => CartActivity::where('status', 'active')->count(),
                    'pending_approvals' => AdminNotification::where('read', false)->count(),
                    'completed_orders' => CartActivity::where('status', 'completed')->count()
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $analytics,
                'message' => 'Fleet analytics retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting fleet analytics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve fleet analytics'
            ], 500);
        }
    }

    /**
     * Export data
     */
    public function exportData(Request $request): JsonResponse
    {
        try {
            $data = [
                'notifications' => AdminNotification::orderBy('created_at', 'desc')->get(),
                'activities' => CartActivity::with('user')->orderBy('created_at', 'desc')->get(),
                'vehicles' => Vehicle::with(['chauffeur', 'bookings'])->get(),
                'users' => User::where('role', 'user')->get(),
                'bookings' => Booking::with(['user', 'vehicle'])->get(),
                'export_date' => now()->toISOString(),
                'exported_by' => auth()->user()->name ?? 'Admin'
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Data exported successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error exporting data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to export data'
            ], 500);
        }
    }

    /**
     * Clear all notifications
     */
    public function clearAllNotifications(Request $request): JsonResponse
    {
        try {
            AdminNotification::truncate();

            return response()->json([
                'success' => true,
                'message' => 'All notifications cleared successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error clearing notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear notifications'
            ], 500);
        }
    }

    /**
     * Process item confirmation
     */
    private function processItemConfirmation(array $data): void
    {
        // Update vehicle status or perform other confirmation actions
        if (isset($data['vehicle_id'])) {
            $vehicle = Vehicle::find($data['vehicle_id']);
            if ($vehicle) {
                // Log the confirmation
                Log::info('Item confirmed by admin', $data);
            }
        }
    }

    /**
     * Process checkout approval
     */
    private function processCheckoutApproval(array $data): void
    {
        // Process checkout approval
        if (isset($data['bookings_count'])) {
            Log::info('Checkout approved by admin', $data);
        }
    }

    /**
     * Process item addition
     */
    private function processItemAddition(array $data): void
    {
        // Process item addition approval
        Log::info('Item addition approved by admin', $data);
    }

    /**
     * Process item removal
     */
    private function processItemRemoval(array $data): void
    {
        // Process item removal approval
        Log::info('Item removal approved by admin', $data);
    }

    /**
     * Log admin action
     */
    private function logAdminAction(string $action, array $data): void
    {
        CartActivity::create([
            'user_id' => auth()->id(),
            'type' => $action,
            'data' => json_encode($data),
            'status' => 'completed',
            'created_at' => now()
        ]);
    }
}
