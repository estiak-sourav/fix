<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partner extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'contact_person',
        'phone',
        'email',
        'trade_domain',
        'technicians_count',
        'coverage_areas',
        'status',
        'partner_type',
        'payment_method',
        'bank_name',
        'account_holder',
        'account_number',
        'branch_name',
        'mfs_number',
        'commission_rate',
        'payout_terms',
        'admin_notes',
        'approved_at',
        'approved_by_admin_id',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'commission_rate' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedByAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by_admin_id');
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'approved' => ['bg' => 'bg-success-subtle', 'color' => '#16a34a', 'label' => 'Approved / Enlisted'],
            'pending' => ['bg' => 'bg-warning-subtle', 'color' => '#d97706', 'label' => 'Pending Approval'],
            'rejected' => ['bg' => 'bg-danger-subtle', 'color' => '#dc2626', 'label' => 'Rejected'],
            'suspended' => ['bg' => 'bg-danger-subtle', 'color' => '#991b1b', 'label' => 'Suspended'],
            default => ['bg' => 'bg-primary-subtle', 'color' => '#3e60d5', 'label' => ucfirst($this->status)],
        };
    }
}
