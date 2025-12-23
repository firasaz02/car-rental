<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get vehicles assigned to this chauffeur
        $assigned_vehicles = Vehicle::where('chauffeur_id', $user->id)
            ->with('latestLocation')
            ->get();

        // Get bookings for assigned vehicles
        $bookings = Booking::whereHas('vehicle', function($query) use ($user) {
                $query->where('chauffeur_id', $user->id);
            })
            ->with(['user', 'vehicle'])
            ->latest()
            ->limit(10)
            ->get();

        $stats = [
            'assigned_vehicles' => $assigned_vehicles->count(),
            'active_bookings' => Booking::whereHas('vehicle', function($query) use ($user) {
                    $query->where('chauffeur_id', $user->id);
                })
                ->whereIn('status', ['pending', 'confirmed'])
                ->count(),
            'completed_trips' => Booking::whereHas('vehicle', function($query) use ($user) {
                    $query->where('chauffeur_id', $user->id);
                })
                ->where('status', 'completed')
                ->count(),
        ];

        return view('user-dashboard', compact('assigned_vehicles', 'bookings', 'stats'));
    }
}
