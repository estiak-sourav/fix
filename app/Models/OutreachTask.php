<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutreachTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'admin_id',
        'created_by_admin_id',
        'completed_by_admin_id',
        'title',
        'task_type',
        'customer_name',
        'customer_phone',
        'priority',
        'status',
        'due_at',
        'completed_at',
        'notes',
        'is_automated',
    ];

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'completed_at' => 'datetime',
            'is_automated' => 'boolean',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function createdByAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by_admin_id');
    }

    public function completedByAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'completed_by_admin_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function markAsCompleted(?Admin $admin = null): bool
    {
        return $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completed_by_admin_id' => $admin?->id,
        ]);
    }

    /**
     * Human-friendly label for task type.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->task_type) {
            'call_unreachable' => 'Unreachable (Retry)',
            'call_back_later' => 'Callback Requested',
            'booking_confirmation' => 'Booking Confirmation',
            'quotation' => 'Send Quotation',
            'inspection' => 'Site Inspection Follow-up',
            default => 'Follow-up Task',
        };
    }
}
