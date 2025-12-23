<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'data',
        'status'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    /**
     * Get the user that owns the cart activity
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for active activities
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for pending activities
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for completed activities
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get activity description
     */
    public function getDescriptionAttribute(): string
    {
        $descriptions = [
            'item_added' => 'Added vehicle to cart',
            'item_removed' => 'Removed vehicle from cart',
            'item_confirmed' => 'Confirmed vehicle selection',
            'checkout_completed' => 'Completed checkout process',
            'cart_cleared' => 'Cleared cart',
            'action_approved' => 'Admin approved action',
            'activity_approved' => 'Admin approved activity'
        ];

        return $descriptions[$this->type] ?? 'Unknown activity';
    }
}
