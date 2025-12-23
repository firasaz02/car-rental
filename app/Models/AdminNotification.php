<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'action',
        'data',
        'read'
    ];

    protected $casts = [
        'data' => 'array',
        'read' => 'boolean'
    ];

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('read', true);
    }

    /**
     * Get notification title
     */
    public function getTitleAttribute(): string
    {
        $titles = [
            'item_confirmed' => 'Item Confirmed',
            'checkout_completed' => 'Checkout Completed',
            'cart_updated' => 'Cart Updated',
            'item_added' => 'Item Added',
            'item_removed' => 'Item Removed',
            'cart_cleared' => 'Cart Cleared'
        ];

        return $titles[$this->action] ?? 'Notification';
    }

    /**
     * Get notification icon
     */
    public function getIconAttribute(): string
    {
        $icons = [
            'item_confirmed' => '✓',
            'checkout_completed' => '🛒',
            'cart_updated' => '🔄',
            'item_added' => '+',
            'item_removed' => '-',
            'cart_cleared' => '🗑️'
        ];

        return $icons[$this->action] ?? '📢';
    }

    /**
     * Mark as read
     */
    public function markAsRead(): void
    {
        $this->update(['read' => true]);
    }
}
