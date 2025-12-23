<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::with(['latestLocation', 'chauffeur'])->where('is_active', true);
        
        // Apply filters
        if ($request->filled('type')) {
            $query->where('vehicle_type', $request->type);
        }
        
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            
            // Get vehicles that are available for the selected dates
            $availableVehicleIds = [];
            $vehicles = $query->get();
            
            foreach ($vehicles as $vehicle) {
                if (Booking::isVehicleAvailable($vehicle->id, $startDate, $endDate)) {
                    $availableVehicleIds[] = $vehicle->id;
                }
            }
            
            $query->whereIn('id', $availableVehicleIds);
        }
        
        $vehicles = $query->get();
        
        // Add availability status to each vehicle
        foreach ($vehicles as $vehicle) {
            $vehicle->is_available = true; // Since we filtered for available vehicles
        }
        
        return view('rent.index', compact('vehicles'));
    }

    public function check(Request $request)
    {
        $data = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);
        
        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);
        
        $available = Booking::isVehicleAvailable($data['vehicle_id'], $start, $end);
        
        return response()->json(['available' => $available]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date|after:now',
            'end_date'   => 'required|date|after:start_date',
        ]);

        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);

        // Re-check availability server-side to prevent race conditions
        if (!Booking::isVehicleAvailable($data['vehicle_id'], $start, $end)) {
            throw ValidationException::withMessages([
                'start_date' => 'This vehicle is not available for the selected dates.',
            ]);
        }

        // Get the vehicle to calculate proper total amount
        $vehicle = Vehicle::find($data['vehicle_id']);

        $booking = Booking::create([
            'user_id'   => Auth::id(),
            'vehicle_id'=> $data['vehicle_id'],
            'chauffeur_id' => $vehicle->chauffeur_id ?? null, // Handle null chauffeur_id
            'start_date'=> $start,
            'end_date'  => $end,
            'status'    => 'pending',
            'total_amount' => $this->calculateTotalAmount($start, $end, $vehicle),
            'notes'     => 'Booking created via web interface'
        ]);

        return back()->with('success', 'Booking created successfully! You will receive a confirmation email shortly.');
    }

    private function calculateTotalAmount(Carbon $start, Carbon $end, Vehicle $vehicle = null)
    {
        $days = $start->diffInDays($end);
        if ($days == 0) $days = 1; // Minimum 1 day
        
        // Use vehicle's daily rate if available, otherwise default
        $dailyRate = $vehicle && $vehicle->daily_rate ? $vehicle->daily_rate : 100;
        
        return $days * $dailyRate;
    }

    // User booking management methods
    public function userBookings()
    {
        $bookings = Booking::with(['vehicle', 'chauffeur'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get(); // Changed to get() for JavaScript compatibility
        
        return view('user.bookings', compact('bookings'));
    }

    public function usingCar()
    {
        $activeBookings = Booking::with(['vehicle.latestLocation'])
            ->where('user_id', Auth::id())
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->where('end_date', '>=', now())
            ->orderBy('start_date', 'desc')
            ->get();
        
        return view('user.using-car', compact('activeBookings'));
    }

    public function cancelBooking(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($booking->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Only pending bookings can be cancelled'], 400);
        }

        $booking->update(['status' => 'cancelled']);

        return response()->json(['success' => true, 'message' => 'Booking cancelled successfully']);
    }

    public function startBooking(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($booking->status !== 'confirmed') {
            return response()->json(['success' => false, 'message' => 'Only confirmed bookings can be started'], 400);
        }

        if (!$booking->start_date->isToday()) {
            return response()->json(['success' => false, 'message' => 'Booking can only be started on the start date'], 400);
        }

        $booking->update(['status' => 'in_progress']);

        return response()->json(['success' => true, 'message' => 'Trip started successfully']);
    }

    public function completeBooking(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($booking->status !== 'in_progress') {
            return response()->json(['success' => false, 'message' => 'Only active trips can be completed'], 400);
        }

        $booking->update(['status' => 'completed']);

        return response()->json(['success' => true, 'message' => 'Trip completed successfully']);
    }

    public function reportIssue(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'issue' => 'required|string|max:1000'
        ]);

        // Here you would typically save the issue to a database
        // For now, we'll just log it
        \Log::info('Issue reported for booking ' . $booking->id . ': ' . $validated['issue']);

        return response()->json(['success' => true, 'message' => 'Issue reported successfully']);
    }

    // API methods for user booking statistics
    public function userBookingStats()
    {
        $userId = Auth::id();
        
        $stats = [
            'total_bookings' => Booking::where('user_id', $userId)->count(),
            'active_bookings' => Booking::where('user_id', $userId)
                ->whereIn('status', ['confirmed', 'in_progress'])
                ->where('end_date', '>=', now())
                ->count(),
            'pending_bookings' => Booking::where('user_id', $userId)
                ->where('status', 'pending')
                ->count(),
        ];

        return response()->json(['success' => true, 'stats' => $stats]);
    }
}
