<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AmcShift extends Model
{
    protected $fillable = [
        'booking_id',
        'partner_id',
        'assigned_partner_crew_id',
        'shift_number',
        'title',
        'scheduled_date',
        'scheduled_time_slot',
        'arrival_window',
        'status',
        'cancelled_by',
        'cancellation_reason',
        'completed_at',
        'completion_notes',
        'crew_notes',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'completed_at' => 'datetime',
            'shift_number' => 'integer',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function assignedCrew(): BelongsTo
    {
        return $this->belongsTo(PartnerCrew::class, 'assigned_partner_crew_id');
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'completed' => '#16a34a',
            'in_progress' => '#2563eb',
            'on_the_way' => '#0284c7',
            'cancelled' => '#dc2626',
            'skipped' => '#f59e0b',
            default => '#64748b',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'Completed',
            'in_progress' => 'In Progress',
            'on_the_way' => 'En Route / On The Way',
            'cancelled' => 'Cancelled',
            'skipped' => 'Skipped',
            default => 'Scheduled',
        };
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    public function getIsCancelledAttribute(): bool
    {
        return in_array($this->status, ['cancelled', 'skipped']);
    }

    public function getIsUpcomingAttribute(): bool
    {
        return in_array($this->status, ['scheduled', 'on_the_way', 'in_progress']);
    }
}
