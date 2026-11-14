<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Partner extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'user_id',
        'company_name',
        'contact_person',
        'phone',
        'password',
        'remember_token',
        'email',
        'avatar',
        'trade_domain',
        'technicians_count',
        'coverage_areas',
        'status',
        'is_currently_offline',
        'offline_until',
        'offline_reason',
        'partner_type',
        'payment_method',
        'bank_name',
        'account_holder',
        'account_number',
        'branch_name',
        'mfs_number',
        'commission_rate',
        'earning_model',
        'payout_rate',
        'payout_terms',
        'admin_notes',
        'notification_preferences',
        'approved_at',
        'approved_by_admin_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'approved_at' => 'datetime',
        'offline_until' => 'datetime',
        'is_currently_offline' => 'boolean',
        'commission_rate' => 'decimal:2',
        'payout_rate' => 'decimal:2',
        'notification_preferences' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedByAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by_admin_id');
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(PartnerLeave::class)->orderBy('start_at');
    }

    public function crews(): HasMany
    {
        return $this->hasMany(PartnerCrew::class)->latest();
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class)->latest();
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(PartnerNotification::class)->latest();
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(PartnerPaymentMethod::class)->orderByDesc('is_default')->latest();
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(PartnerPayout::class)->latest();
    }

    public function getTotalEarnedAttribute(): float
    {
        if ($this->earning_model === 'monthly') {
            return (float) ($this->payout_rate ?? 0);
        }

        $completedBookings = $this->bookings()
            ->where(function ($q) {
                $q->where('status', 'completed')
                    ->orWhere('partner_job_status', 'completed');
            })
            ->get();

        return (float) $completedBookings->sum(function ($b) {
            return $this->calculateBookingEarning($b);
        });
    }

    /**
     * Total cash/payments collected directly on site by crew from customers.
     */
    public function getCashCollectedByCrewAttribute(): float
    {
        return (float) $this->bookings()
            ->where(function ($q) {
                $q->where('status', 'completed')
                    ->orWhere('partner_job_status', 'completed');
            })
            ->where('payment_collected_by', 'crew')
            ->get()
            ->sum(function ($b) {
                return (float) ($b->collected_amount ?? $b->final_cost ?? $b->estimated_cost ?? 0);
            });
    }

    /**
     * Total payouts disbursed by Admin to this Partner.
     */
    public function getPaidToCrewAttribute(): float
    {
        $payoutSum = (float) $this->payouts()
            ->where(function ($q) {
                $q->where('direction', 'admin_to_partner')
                    ->orWhereNull('direction');
            })
            ->where('status', 'paid')
            ->sum('amount');

        // Also include any legacy bookings marked as paid where no payout transaction exists
        if ($payoutSum === 0.0) {
            $legacySum = (float) $this->bookings()
                ->where('partner_payout_status', 'paid')
                ->where(function ($q) {
                    $q->where('status', 'completed')
                        ->orWhere('partner_job_status', 'completed');
                })
                ->get()
                ->sum(function ($b) {
                    return (float) ($b->partner_payout_amount ?? $this->calculateBookingEarning($b));
                });

            return $legacySum;
        }

        return $payoutSum;
    }

    /**
     * Total settlements paid by Partner to Admin (approved).
     */
    public function getPaidByCrewAttribute(): float
    {
        return (float) $this->payouts()
            ->where('direction', 'partner_to_admin')
            ->where('status', 'paid')
            ->sum('amount');
    }

    public function getTotalPaidAttribute(): float
    {
        return $this->paid_to_crew;
    }

    /**
     * Net financial balance:
     * (Total Earnings + Settled Payments to Admin) - (Client Cash Collected by Crew + Admin Payouts Received).
     *
     * Positive (> 0): Admin owes Crew (Admin er kache crew pabe).
     * Negative (< 0): Crew owes Admin (Crew er kache admin pabe).
     * Zero (= 0): Fully balanced & settled.
     */
    public function getNetBalanceAttribute(): float
    {
        return (float) (($this->total_earned + $this->paid_by_crew) - ($this->cash_collected_by_crew + $this->paid_to_crew));
    }

    public function getAdminOwesCrewAttribute(): bool
    {
        return $this->net_balance > 0.001;
    }

    public function getCrewOwesAdminAttribute(): bool
    {
        return $this->net_balance < -0.001;
    }

    public function getIsSettledAttribute(): bool
    {
        return abs($this->net_balance) <= 0.001;
    }

    /**
     * Balance available for Crew to withdraw / for Admin to disburse.
     */
    public function getPayableBalanceAttribute(): float
    {
        return max(0.0, $this->net_balance);
    }

    /**
     * Dues balance that Crew owes Admin.
     */
    public function getCrewDueAmountAttribute(): float
    {
        return max(0.0, -1.0 * $this->net_balance);
    }

    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    public function ensureDefaultPaymentMethod(): ?PartnerPaymentMethod
    {
        if ($this->paymentMethods()->exists()) {
            return $this->paymentMethods()->where('is_default', true)->first() ?: $this->paymentMethods()->first();
        }

        if ($this->payment_method) {
            $isMfs = in_array($this->payment_method, ['bkash', 'nagad', 'rocket']);
            $accNum = $isMfs ? ($this->mfs_number ?: $this->phone) : $this->account_number;

            if ($accNum) {
                return $this->paymentMethods()->create([
                    'method_type' => $this->payment_method,
                    'account_number' => $accNum,
                    'account_name' => $this->account_holder ?: $this->company_name,
                    'number_type' => 'personal',
                    'bank_name' => $this->bank_name,
                    'branch_name' => $this->branch_name,
                    'is_default' => true,
                ]);
            }
        }

        return null;
    }

    public function isVendor(): bool
    {
        return $this->partner_type === 'company';
    }

    public function isSoloCrew(): bool
    {
        return $this->partner_type === 'individual';
    }

    public function getEarningModelLabelAttribute(): string
    {
        return match ($this->earning_model) {
            'hourly' => 'Hourly Rate',
            'daily' => 'Daily Rate',
            'monthly' => 'Monthly Retainer',
            'per_job' => 'Fixed Per Job',
            default => 'Commission (%)',
        };
    }

    public function getFormattedRateAttribute(): string
    {
        return match ($this->earning_model) {
            'hourly' => '৳ '.number_format((float) ($this->payout_rate ?? 0), 2).' / hr',
            'daily' => '৳ '.number_format((float) ($this->payout_rate ?? 0), 2).' / day',
            'monthly' => '৳ '.number_format((float) ($this->payout_rate ?? 0), 2).' / month',
            'per_job' => '৳ '.number_format((float) ($this->payout_rate ?? 0), 2).' / job',
            default => number_format((float) ($this->payout_rate ?? $this->commission_rate ?: 15.00), 2).'%',
        };
    }

    /**
     * Calculate partner's net earning for a specific booking.
     */
    public function calculateBookingEarning(Booking $booking): float
    {
        if ($booking->partner_payout_amount !== null) {
            return (float) $booking->partner_payout_amount;
        }

        $rate = (float) ($this->payout_rate ?? $this->commission_rate ?? 0);

        return match ($this->earning_model) {
            'hourly' => (float) ($rate * (float) ($booking->partner_worked_hours ?: 1)),
            'daily' => (float) $rate,
            'per_job' => (float) $rate,
            'monthly' => 0.0,
            default => (float) ((($booking->final_cost ?? $booking->estimated_cost ?? 0) * (float) ($rate ?: 15.00)) / 100),
        };
    }

    /**
     * Check if the partner is currently available or at a specific datetime.
     */
    public function isAvailableAt(?Carbon $targetTime = null): bool
    {
        $time = $targetTime ?? now();

        // 1. Check quick offline toggle
        if ($this->is_currently_offline) {
            if ($this->offline_until === null || $this->offline_until->isAfter($time)) {
                return false;
            }
        }

        // 2. Check scheduled leave blocks
        $hasLeave = $this->leaves()
            ->where('start_at', '<=', $time)
            ->where('end_at', '>=', $time)
            ->exists();

        return ! $hasLeave;
    }

    /**
     * Check if partner currently has an active job underway.
     */
    public function getActiveJobsAttribute()
    {
        return $this->bookings()
            ->whereIn('partner_job_status', ['on_the_way', 'in_progress'])
            ->get();
    }

    /**
     * Notification preference helper.
     */
    public function wantsNotification(string $key): bool
    {
        if (empty($this->email)) {
            return false;
        }

        $prefs = $this->notification_preferences ?? [];

        return (bool) ($prefs[$key] ?? true);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'approved' => ['bg' => 'bg-success-subtle', 'color' => '#16a34a', 'label' => 'Approved'],
            'pending' => ['bg' => 'bg-warning-subtle', 'color' => '#d97706', 'label' => 'Pending Approval'],
            'rejected' => ['bg' => 'bg-danger-subtle', 'color' => '#dc2626', 'label' => 'Rejected'],
            'suspended' => ['bg' => 'bg-danger-subtle', 'color' => '#991b1b', 'label' => 'Suspended'],
            default => ['bg' => 'bg-primary-subtle', 'color' => '#3e60d5', 'label' => ucfirst($this->status)],
        };
    }

    /**
     * Get avatar full URL if present.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (! $this->avatar) {
            return null;
        }

        return str_starts_with($this->avatar, 'http') ? $this->avatar : asset('storage/'.$this->avatar);
    }

    /**
     * Get partner display initials for avatar placeholder.
     */
    public function getInitialsAttribute(): string
    {
        $name = trim($this->contact_person ?: $this->company_name);
        $words = preg_split('/\s+/', $name);
        if (empty($words) || empty($words[0])) {
            return 'CR';
        }
        if (count($words) === 1) {
            return strtoupper(substr($words[0], 0, 2));
        }

        return strtoupper(substr($words[0], 0, 1).substr(end($words), 0, 1));
    }
}
