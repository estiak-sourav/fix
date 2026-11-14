<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerPayout extends Model
{
    protected $fillable = [
        'payout_number',
        'direction',
        'initiated_by',
        'partner_id',
        'partner_payment_method_id',
        'admin_payment_method_id',
        'payment_method_type',
        'amount',
        'status',
        'payment_method_details',
        'transaction_reference',
        'admin_notes',
        'partner_notes',
        'proof_file',
        'requested_at',
        'paid_at',
        'processed_by_admin_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'requested_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public static function generatePayoutNumber(string $direction = 'admin_to_partner'): string
    {
        $prefix = $direction === 'partner_to_admin' ? 'SETTLE-'.date('Ymd') : 'PAY-'.date('Ymd');
        $count = static::whereDate('created_at', today())->count() + 1;

        return sprintf('%s-%04d', $prefix, $count);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function getMethodAttribute(): ?string
    {
        return $this->payment_method_type;
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PartnerPaymentMethod::class, 'partner_payment_method_id');
    }

    public function adminPaymentMethod(): BelongsTo
    {
        return $this->belongsTo(AdminPaymentMethod::class, 'admin_payment_method_id');
    }

    public function processedByAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'processed_by_admin_id');
    }

    public function scopePayouts(Builder $query): Builder
    {
        return $query->where('direction', 'admin_to_partner');
    }

    public function scopeSettlements(Builder $query): Builder
    {
        return $query->where('direction', 'partner_to_admin');
    }

    public function isPartnerToAdmin(): bool
    {
        return $this->direction === 'partner_to_admin';
    }

    public function isAdminToPartner(): bool
    {
        return $this->direction !== 'partner_to_admin';
    }

    public function getStatusBadgeAttribute(): array
    {
        if ($this->direction === 'partner_to_admin') {
            return match ($this->status) {
                'paid' => ['bg' => '#ecfdf5', 'color' => '#059669', 'label' => 'Payment Confirmed / Settled'],
                'pending' => ['bg' => '#fffbeb', 'color' => '#d97706', 'label' => 'Verification Pending'],
                'requested' => ['bg' => '#eff6ff', 'color' => '#2563eb', 'label' => 'Payment Requested by Admin'],
                'rejected' => ['bg' => '#fef2f2', 'color' => '#dc2626', 'label' => 'Verification Rejected'],
                'cancelled' => ['bg' => '#f1f5f9', 'color' => '#64748b', 'label' => 'Cancelled'],
                default => ['bg' => '#f8fafc', 'color' => '#475569', 'label' => ucfirst($this->status)],
            };
        }

        return match ($this->status) {
            'paid' => ['bg' => '#ecfdf5', 'color' => '#059669', 'label' => 'Disbursed / Paid'],
            'pending' => ['bg' => '#fffbeb', 'color' => '#d97706', 'label' => 'Requested / Pending'],
            'rejected' => ['bg' => '#fef2f2', 'color' => '#dc2626', 'label' => 'Rejected'],
            'cancelled' => ['bg' => '#f1f5f9', 'color' => '#64748b', 'label' => 'Cancelled'],
            default => ['bg' => '#f8fafc', 'color' => '#475569', 'label' => ucfirst($this->status)],
        };
    }
}
