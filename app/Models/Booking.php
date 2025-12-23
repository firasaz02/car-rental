<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'chauffeur_id',
        'start_date',
        'end_date',
        'status',
        'total_amount',
        'notes',
        'pickup_location',
        'dropoff_location'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'total_amount' => 'decimal:2'
    ];

    /**
     * Get the user that owns the booking
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the vehicle for this booking
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the chauffeur assigned to this booking
     */
    public function chauffeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chauffeur_id');
    }

    /**
     * Query scopes for common booking filters
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed', 'in_progress']);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function scopeToday($query)
    {
        return $query->whereDate('start_date', today());
    }

    /**
     * Check if a vehicle is available for booking during a specific time range
     * Uses industry-standard overlap detection formula
     */
    public static function isVehicleAvailable(int $vehicleId, Carbon $start, Carbon $end): bool
    {
        $conflicts = self::where('vehicle_id', $vehicleId)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($start, $end) {
                // Industry-standard overlap detection formula:
                // Two bookings overlap if: end_date >= start AND start_date <= end
                $query->whereDate('end_date', '>=', $start)
                      ->whereDate('start_date', '<=', $end);
            })
            ->exists();

        return !$conflicts;
    }

    /**
     * Get all conflicting bookings for a vehicle in a time range
     */
    public static function getConflicts(int $vehicleId, Carbon $start, Carbon $end, ?int $excludeBookingId = null)
    {
        $query = self::where('vehicle_id', $vehicleId)
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($start, $end) {
                $q->whereDate('end_date', '>=', $start)
                  ->whereDate('start_date', '<=', $end);
            });

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->get();
    }

    /**
     * Check if this booking can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']) && 
               $this->start_date->isFuture();
    }

    /**
     * Check if this booking can be started
     */
    public function canBeStarted(): bool
    {
        return $this->status === 'confirmed' && 
               $this->start_date->isToday() && 
               $this->start_date->isPast();
    }

    /**
     * Check if this booking can be completed
     */
    public function canBeCompleted(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Get the duration of the booking in days
     */
    public function getDurationInDays(): int
    {
        return $this->start_date->diffInDays($this->end_date);
    }

    /**
     * Get the duration of the booking in hours
     */
    public function getDurationInHours(): int
    {
        return $this->start_date->diffInHours($this->end_date);
    }

    /**
     * Check if the booking is currently active
     */
    public function isActive(): bool
    {
        return in_array($this->status, ['pending', 'confirmed', 'in_progress']);
    }

    /**
     * Check if the booking is in the past
     */
    public function isPast(): bool
    {
        return $this->end_date->isPast();
    }

    /**
     * Check if the booking is in the future
     */
    public function isFuture(): bool
    {
        return $this->start_date->isFuture();
    }

    /**
     * Check if the booking is happening today
     */
    public function isToday(): bool
    {
        return $this->start_date->isToday() || $this->end_date->isToday();
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeClassAttribute(): string
    {
        $classes = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'in_progress' => 'bg-green-100 text-green-800',
            'completed' => 'bg-gray-100 text-gray-800',
            'cancelled' => 'bg-red-100 text-red-800'
        ];

        return $classes[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get status text for UI
     */
    public function getStatusTextAttribute(): string
    {
        $texts = [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];

        return $texts[$this->status] ?? 'Unknown';
    }

    /**
     * Calculate total amount based on duration and vehicle rate
     */
    public function calculateTotalAmount(): float
    {
        $hours = $this->getDurationInHours();
        $ratePerHour = $this->vehicle->rate_per_hour ?? 50; // Default rate
        
        return $hours * $ratePerHour;
    }

    /**
     * Auto-calculate total amount before saving
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($booking) {
            // Auto-calculate total amount if not set
            if (!$booking->total_amount) {
                $booking->total_amount = $booking->calculateTotalAmount();
            }

            // Validate booking dates
            if ($booking->start_date >= $booking->end_date) {
                throw new \InvalidArgumentException('End date must be after start date');
            }

            // Check for conflicts (only for new bookings or status changes)
            if ($booking->isDirty(['vehicle_id', 'start_date', 'end_date', 'status']) && 
                $booking->status !== 'cancelled') {
                
                $conflicts = self::getConflicts(
                    $booking->vehicle_id, 
                    $booking->start_date, 
                    $booking->end_date, 
                    $booking->exists ? $booking->id : null
                );

                if ($conflicts->count() > 0) {
                    throw new \InvalidArgumentException('Vehicle is not available during the selected time period');
                }
            }
        });
    }

    /**
     * Get bookings for a specific date range
     */
    public static function getBookingsInRange(Carbon $start, Carbon $end)
    {
        return self::where(function ($query) use ($start, $end) {
            $query->whereBetween('start_date', [$start, $end])
                  ->orWhereBetween('end_date', [$start, $end])
                  ->orWhere(function ($q) use ($start, $end) {
                      $q->where('start_date', '<=', $start)
                        ->where('end_date', '>=', $end);
                  });
        })->with(['user', 'vehicle']);
    }

    /**
     * Get upcoming bookings for a user
     */
    public static function getUpcomingForUser(int $userId)
    {
        return self::where('user_id', $userId)
            ->where('start_date', '>', now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_date')
            ->get();
    }

    /**
     * Get active bookings for a user
     */
    public static function getActiveForUser(int $userId)
    {
        return self::where('user_id', $userId)
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->orderBy('start_date')
            ->get();
    }

    /**
     * Get booking statistics
     */
    public static function getStatistics()
    {
        return [
            'total' => self::count(),
            'pending' => self::pending()->count(),
            'confirmed' => self::confirmed()->count(),
            'completed' => self::completed()->count(),
            'cancelled' => self::cancelled()->count(),
            'today' => self::today()->count(),
            'upcoming' => self::upcoming()->count()
        ];
    }
}