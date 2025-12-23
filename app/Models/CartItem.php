<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'confirmed',
        'confirmed_at'
    ];

    protected $casts = [
        'confirmed' => 'boolean',
        'confirmed_at' => 'datetime'
    ];

    /**
     * Get the user that owns the cart item
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the vehicle for this cart item
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Scope for confirmed items
     */
    public function scopeConfirmed($query)
    {
        return $query->where('confirmed', true);
    }

    /**
     * Scope for unconfirmed items
     */
    public function scopeUnconfirmed($query)
    {
        return $query->where('confirmed', false);
    }

    /**
     * Confirm the cart item
     */
    public function confirm(): void
    {
        $this->update([
            'confirmed' => true,
            'confirmed_at' => now()
        ]);
    }
}
