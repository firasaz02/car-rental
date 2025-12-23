<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_number',
        'driver_name',
        'vehicle_type',
        'phone',
        'make',
        'model',
        'year',
        'license_plate',
        'vin',
        'color',
        'mileage',
        'fuel_type',
        'transmission_type',
        'last_maintenance_date',
        'next_maintenance_date',
        'insurance_policy_number',
        'insurance_expiry_date',
        'registration_expiry_date',
        'notes',
        'is_active',
        'chauffeur_id',
        'rate_per_hour',
        'image_url',
        'available_for_cart',
        'cart_usage_count',
        'type',
        'capacity',
        'daily_rate',
        'status',
        'description',
        'features'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'insurance_expiry_date' => 'date',
        'registration_expiry_date' => 'date',
        'rate_per_hour' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'available_for_cart' => 'boolean',
        'cart_usage_count' => 'integer',
        'capacity' => 'integer',
        'mileage' => 'integer',
        'year' => 'integer'
    ];

    /**
     * Get the chauffeur assigned to this vehicle
     */
    public function chauffeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chauffeur_id');
    }

    /**
     * Get all bookings for this vehicle
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get all vehicle locations
     */
    public function locations(): HasMany
    {
        return $this->hasMany(VehicleLocation::class);
    }

    /**
     * Get the latest location for this vehicle
     */
    public function latestLocation(): HasOne
    {
        return $this->hasOne(VehicleLocation::class)->latest();
    }

    /**
     * Get recent locations for this vehicle
     */
    public function recentLocations($limit = 10)
    {
        return $this->locations()->latest()->limit($limit)->get();
    }

    /**
     * Get the current active booking for this vehicle
     */
    public function currentBooking()
    {
        return $this->bookings()
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();
    }

    /**
     * Check if vehicle is available for booking during a specific time range
     */
    public function isAvailable(Carbon $start, Carbon $end): bool
    {
        return Booking::isVehicleAvailable($this->id, $start, $end);
    }

    /**
     * Get all bookings for this vehicle in a specific time range
     */
    public function getBookingsInRange(Carbon $start, Carbon $end)
    {
        return $this->bookings()
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                      ->orWhereBetween('end_date', [$start, $end])
                      ->orWhere(function ($q) use ($start, $end) {
                          $q->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                      });
            })
            ->where('status', '!=', 'cancelled')
            ->get();
    }

    /**
     * Check if vehicle is currently in use
     */
    public function isInUse(): bool
    {
        return $this->currentBooking() !== null;
    }

    /**
     * Get vehicle status (available, in-use, inactive)
     */
    public function getStatus(): string
    {
        if (!$this->is_active) {
            return 'inactive';
        }

        if ($this->isInUse()) {
            return 'in-use';
        }

        return 'available';
    }

    /**
     * Calculate distance between two GPS coordinates using Haversine formula
     */
    public static function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Distance in kilometers
    }

    /**
     * Calculate total distance traveled by this vehicle
     */
    public function getTotalDistanceTraveled(): float
    {
        $locations = $this->locations()->orderBy('recorded_at')->get();
        
        if ($locations->count() < 2) {
            return 0;
        }

        $totalDistance = 0;
        
        for ($i = 1; $i < $locations->count(); $i++) {
            $prev = $locations[$i - 1];
            $curr = $locations[$i];
            
            $distance = self::calculateDistance(
                $prev->latitude,
                $prev->longitude,
                $curr->latitude,
                $curr->longitude
            );
            
            $totalDistance += $distance;
        }

        return round($totalDistance, 2);
    }

    /**
     * Get distance traveled in a specific time period
     */
    public function getDistanceTraveledInPeriod(Carbon $start, Carbon $end): float
    {
        $locations = $this->locations()
            ->whereBetween('recorded_at', [$start, $end])
            ->orderBy('recorded_at')
            ->get();
        
        if ($locations->count() < 2) {
            return 0;
        }

        $totalDistance = 0;
        
        for ($i = 1; $i < $locations->count(); $i++) {
            $prev = $locations[$i - 1];
            $curr = $locations[$i];
            
            $distance = self::calculateDistance(
                $prev->latitude,
                $prev->longitude,
                $curr->latitude,
                $curr->longitude
            );
            
            $totalDistance += $distance;
        }

        return round($totalDistance, 2);
    }

    /**
     * Get vehicle icon based on type
     */
    public function getVehicleIconAttribute(): string
    {
        $icons = [
            'Sedan' => '🚙',
            'SUV' => '🚗',
            'Truck' => '🚛',
            'Van' => '🚐',
            'Motorcycle' => '🏍️',
            'Bus' => '🚌',
            'Taxi' => '🚕',
            'Hatchback' => '🚘',
            'Pickup' => '🛻',
            'Emergency' => '🚑',
            'Car' => '🚙',
            'Luxury' => '🏎️'
        ];
        
        return $icons[$this->vehicle_type] ?? '🚙';
    }

    /**
     * Get the image URL with fallback to default image
     */
    public function getImageUrlAttribute($value): string
    {
        if ($value) {
            // If it's already a full URL, return as is
            if (str_starts_with($value, 'http')) {
                return $value;
            }
            // If it's a storage path, return the full URL
            if (str_starts_with($value, 'storage/')) {
                return asset($value);
            }
            // If it's a relative path, make it absolute
            return asset($value);
        }
        
        // Return default vehicle image if no image is uploaded
        return asset('/images/default-vehicle.svg');
    }

    /**
     * Get vehicle status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        $status = $this->getStatus();
        
        $classes = [
            'available' => 'bg-green-100 text-green-800',
            'in-use' => 'bg-blue-100 text-blue-800',
            'inactive' => 'bg-red-100 text-red-800'
        ];

        return $classes[$status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get vehicle status text
     */
    public function getStatusTextAttribute(): string
    {
        $status = $this->getStatus();
        
        $texts = [
            'available' => 'Available',
            'in-use' => 'In Use',
            'inactive' => 'Inactive'
        ];

        return $texts[$status] ?? 'Unknown';
    }

    /**
     * Check if vehicle needs maintenance
     */
    public function needsMaintenance(): bool
    {
        if (!$this->next_maintenance_date) {
            return false;
        }

        return $this->next_maintenance_date->isPast();
    }

    /**
     * Check if vehicle insurance is expired
     */
    public function isInsuranceExpired(): bool
    {
        if (!$this->insurance_expiry_date) {
            return false;
        }

        return $this->insurance_expiry_date->isPast();
    }

    /**
     * Check if vehicle registration is expired
     */
    public function isRegistrationExpired(): bool
    {
        if (!$this->registration_expiry_date) {
            return false;
        }

        return $this->registration_expiry_date->isPast();
    }

    /**
     * Get vehicle statistics for admin dashboard
     */
    public function getStatistics(): array
    {
        $bookings = $this->bookings();
        
        return [
            'total_bookings' => $bookings->count(),
            'completed_bookings' => $bookings->where('status', 'completed')->count(),
            'cancelled_bookings' => $bookings->where('status', 'cancelled')->count(),
            'total_revenue' => $bookings->where('status', 'completed')->sum('total_amount'),
            'average_booking_duration' => $bookings->where('status', 'completed')->avg('duration_hours'),
            'last_booking_date' => $bookings->latest()->first()?->created_at,
            'total_distance_traveled' => $this->getTotalDistanceTraveled(),
            'current_status' => $this->getStatus()
        ];
    }

    /**
     * Scope for active vehicles
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for inactive vehicles
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope for assigned vehicles
     */
    public function scopeAssigned($query)
    {
        return $query->whereNotNull('chauffeur_id');
    }

    /**
     * Scope for unassigned vehicles
     */
    public function scopeUnassigned($query)
    {
        return $query->whereNull('chauffeur_id');
    }

    /**
     * Scope for vehicles by type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('vehicle_type', $type);
    }

    /**
     * Scope for vehicles needing maintenance
     */
    public function scopeNeedingMaintenance($query)
    {
        return $query->where('next_maintenance_date', '<=', now());
    }

    /**
     * Scope for vehicles with expired insurance
     */
    public function scopeWithExpiredInsurance($query)
    {
        return $query->where('insurance_expiry_date', '<=', now());
    }

    /**
     * Scope for vehicles with expired registration
     */
    public function scopeWithExpiredRegistration($query)
    {
        return $query->where('registration_expiry_date', '<=', now());
    }

    /**
     * Get vehicles available for booking in a time range
     */
    public static function getAvailableInRange(Carbon $start, Carbon $end)
    {
        return self::active()->get()->filter(function ($vehicle) use ($start, $end) {
            return $vehicle->isAvailable($start, $end);
        });
    }

    /**
     * Get top vehicles by booking count
     */
    public static function getTopByBookings($limit = 10)
    {
        return self::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get vehicles by chauffeur
     */
    public static function getByChauffeur(int $chauffeurId)
    {
        return self::where('chauffeur_id', $chauffeurId)->get();
    }
}