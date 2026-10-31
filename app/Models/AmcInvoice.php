<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AmcInvoice extends Model
{
    protected $fillable = [
        'booking_id',
        'invoice_number',
        'billing_cycle_label',
        'period_start',
        'period_end',
        'due_date',
        'amount',
        'status',
        'paid_at',
        'payment_method',
        'payment_reference',
        'collected_by_admin_id',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'due_date' => 'date',
            'paid_at' => 'datetime',
            'amount' => 'decimal:2',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function collectedByAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'collected_by_admin_id');
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', 'paid');
    }

    public function scopeUnpaid(Builder $query): Builder
    {
        return $query->where('status', 'unpaid');
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('status', 'overdue')
            ->orWhere(function ($q) {
                $q->where('status', 'unpaid')->where('due_date', '<', today());
            });
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status !== 'paid' && $this->due_date && $this->due_date < today();
    }

    public function getEffectiveStatusAttribute(): string
    {
        if ($this->status === 'paid') {
            return 'paid';
        }

        if ($this->is_overdue) {
            return 'overdue';
        }

        return 'unpaid';
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->effective_status) {
            'paid' => '#10b981', // emerald green
            'overdue' => '#ef4444', // red
            default => '#f59e0b', // amber
        };
    }
}

