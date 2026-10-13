<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'booking_number',
        'user_id',
        'service_id',
        'amc_plan_id',
        'service_type',
        'customer_name',
        'customer_email',
        'customer_phone',
        'company_name',
        'property_type',
        'estimated_sqft',
        'division',
        'district',
        'area',
        'street_address',
        'preferred_date',
        'preferred_time_slot',
        'urgency',
        'problem_description',
        'attached_images',
        'status',
        'admin_notes',
        'assigned_team',
        'estimated_cost',
        'final_cost',
        'payment_status',
        'accepted_at',
        'completed_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'attached_images' => 'array',
            'preferred_date' => 'date',
            'estimated_sqft' => 'integer',
            'estimated_cost' => 'decimal:2',
            'final_cost' => 'decimal:2',
            'accepted_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function amcPlan(): BelongsTo
    {
        return $this->belongsTo(AmcPlan::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(BookingStatusLog::class)->latest();
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(AdminNotification::class);
    }

    public static function generateBookingNumber(): string
    {
        $datePrefix = date('Ymd');
        $random = strtoupper(bin2hex(random_bytes(2)));
        $count = static::whereDate('created_at', today())->count() + 1;
        return sprintf('FIX-%s-%04d-%s', $datePrefix, $count, $random);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending_review' => 'Pending Review',
            'accepted' => 'Accepted',
            'assigned' => 'Team Assigned',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'pending_review' => '#f59e0b', // Amber
            'accepted' => '#3b82f6', // Blue
            'assigned' => '#8b5cf6', // Indigo/Purple
            'in_progress' => '#06b6d4', // Cyan
            'completed' => '#10b981', // Green
            'cancelled' => '#ef4444', // Red
            default => '#6b7280',
        };
    }
}
