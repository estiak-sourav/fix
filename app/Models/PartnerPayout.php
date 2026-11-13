<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerPayout extends Model
{
    protected $fillable = [
        'payout_number',
        'partner_id',
        'partner_payment_method_id',
        'amount',
        'status',
        'payment_method_details',
        'transaction_reference',
        'admin_notes',
        'partner_notes',
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

    public static function generatePayoutNumber(): string
    {
        $prefix = 'PAY-'.date('Ymd');
        $count = static::whereDate('created_at', today())->count() + 1;

        return sprintf('%s-%04d', $prefix, $count);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PartnerPaymentMethod::class, 'partner_payment_method_id');
    }

    public function processedByAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'processed_by_admin_id');
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'paid' => ['bg' => '#ecfdf5', 'color' => '#059669', 'label' => 'Disbursed / Paid'],
            'pending' => ['bg' => '#fffbeb', 'color' => '#d97706', 'label' => 'Requested / Pending'],
            'rejected' => ['bg' => '#fef2f2', 'color' => '#dc2626', 'label' => 'Rejected'],
            'cancelled' => ['bg' => '#f1f5f9', 'color' => '#64748b', 'label' => 'Cancelled'],
            default => ['bg' => '#f8fafc', 'color' => '#475569', 'label' => ucfirst($this->status)],
        };
    }
}
