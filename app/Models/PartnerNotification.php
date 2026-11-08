<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'booking_id',
        'type',
        'title',
        'message',
        'action_url',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * The partner recipient of this notification.
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Associated booking / service order if applicable.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Scope only unread notifications.
     */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope only read notifications.
     */
    public function scopeRead(Builder $query): Builder
    {
        return $query->where('is_read', true);
    }

    /**
     * Mark this notification as read.
     */
    public function markAsRead(): bool
    {
        return $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Get icon and color badge for notification type.
     */
    public function getTypeBadgeAttribute(): array
    {
        return match ($this->type) {
            'job_assigned' => [
                'color' => '#0d9488',
                'bg' => '#f0fdfa',
                'border' => '#ccfbf1',
                'label' => 'New Order',
            ],
            'job_updated' => [
                'color' => '#2563eb',
                'bg' => '#eff6ff',
                'border' => '#dbeafe',
                'label' => 'Schedule Update',
            ],
            'job_cancelled' => [
                'color' => '#ef4444',
                'bg' => '#fef2f2',
                'border' => '#fee2e2',
                'label' => 'Order Cancelled',
            ],
            'account_approved' => [
                'color' => '#16a34a',
                'bg' => '#f0fdf4',
                'border' => '#dcfce7',
                'label' => 'Account Active',
            ],
            'payout_ready' => [
                'color' => '#d97706',
                'bg' => '#fffbeb',
                'border' => '#fef3c7',
                'label' => 'Payout',
            ],
            default => [
                'color' => '#475569',
                'bg' => '#f8fafc',
                'border' => '#e2e8f0',
                'label' => 'System',
            ],
        };
    }
}

