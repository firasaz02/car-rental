<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'active_bookings' => Booking::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->count(),
            'completed_bookings' => Booking::where('user_id', $user->id)
                ->where('status', 'completed')
                ->count(),
        ];

        $recent_bookings = Booking::where('user_id', $user->id)
            ->with('vehicle')
            ->latest()
            ->limit(5)
            ->get();

        $available_vehicles = Vehicle::where('is_active', true)
            ->with('latestLocation')
            ->limit(6)
            ->get();

        return view('user-dashboard', compact('stats', 'recent_bookings', 'available_vehicles'));
    }
}
