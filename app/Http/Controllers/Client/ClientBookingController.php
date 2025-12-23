<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ClientBookingController extends Controller
{
    /**
     * Display rent vehicles page with availability check.
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', today()->format('Y-m-d'));
        $endDate = $request->input('end_date', today()->addDays(1)->format('Y-m-d'));
        $vehicleType = $request->input('type');

        // Build query
        $query = Vehicle::with(['latestLocation', 'chauffeur'])
            ->where('is_active', true);

        // Filter by type if provided
        if ($vehicleType) {
            $query->where('vehicle_type', $vehicleType);
        }

        $vehicles = $query->get();

        // Check availability for each vehicle
        foreach ($vehicles as $vehicle) {
            $vehicle->is_available = Booking::isVehicleAvailable(
                $vehicle->id, 
                Carbon::parse($startDate), 
                Carbon::parse($endDate)
            );
        }

        return view('rent.index', compact('vehicles', 'startDate', 'endDate'));
    }

    /**
     * Store a new booking.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Check vehicle is active
        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        
        if (!$vehicle->is_active) {
            throw ValidationException::withMessages([
                'vehicle_id' => 'This vehicle is not available.',
            ]);
        }

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        // Re-check availability server-side
        if (!Booking::isVehicleAvailable($validated['vehicle_id'], $startDate, $endDate)) {
            throw ValidationException::withMessages([
                'start_date' => 'This vehicle is not available for the selected dates.',
            ]);
        }

        // Calculate total amount
        $days = $startDate->diffInDays($endDate);
        if ($days == 0) $days = 1; // Minimum 1 day
        $dailyRate = $vehicle->daily_rate ?: 100;
        $totalAmount = $days * $dailyRate;

        // Create booking
        Booking::create([
            'user_id' => Auth::id(),
            'vehicle_id' => $validated['vehicle_id'],
            'chauffeur_id' => $vehicle->chauffeur_id ?? null, // Handle null chauffeur_id
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'notes' => 'Booking created via client interface'
        ]);

        return redirect()->route('client.bookings.mine')
            ->with('success', 'Booking request submitted successfully! Please wait for confirmation.');
    }

    /**
     * Display user's bookings.
     */
    public function myBookings()
    {
        $bookings = Booking::with(['vehicle', 'chauffeur'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get(); // Changed from paginate(20) to get() for JavaScript compatibility

        return view('user.bookings', compact('bookings'));
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->user_id !== Auth::id()) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            abort(403, 'Unauthorized');
        }

        if (!$booking->canBeCancelled()) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'This booking cannot be cancelled.']);
            }
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        $booking->update(['status' => 'cancelled']);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Booking cancelled successfully.']);
        }
        return back()->with('success', 'Booking cancelled successfully.');
    }

    /**
     * Check vehicle availability via AJAX.
     */
    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        $available = Booking::isVehicleAvailable($validated['vehicle_id'], $startDate, $endDate);

        return response()->json([
            'available' => $available,
            'message' => $available ? 'Vehicle is available' : 'Vehicle is not available for these dates'
        ]);
    }
}
