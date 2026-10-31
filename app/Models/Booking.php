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
        'call_status',
        'call_notes',
        'completion_feedback',
        'review_rating',
        'review_comment',
        'reviewed_at',
        'accepted_at',
        'completed_at',
        'cancelled_at',
        'contract_start_date',
        'contract_end_date',
        'agreement_document_path',
        'amc_package_notes',
        'contract_status',
        'billing_cycle',
        'recurring_amount',
        'billing_day',
        'next_billing_date',
    ];

    protected function casts(): array
    {
        return [
            'attached_images' => 'array',
            'preferred_date' => 'date',
            'contract_start_date' => 'date',
            'contract_end_date' => 'date',
            'next_billing_date' => 'date',
            'estimated_sqft' => 'integer',
            'estimated_cost' => 'decimal:2',
            'final_cost' => 'decimal:2',
            'recurring_amount' => 'decimal:2',
            'billing_day' => 'integer',
            'review_rating' => 'integer',
            'reviewed_at' => 'datetime',
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

    public function outreachTasks(): HasMany
    {
        return $this->hasMany(OutreachTask::class)->latest();
    }

    public function amcInvoices(): HasMany
    {
        return $this->hasMany(AmcInvoice::class)->orderBy('due_date');
    }

    public function getIsAmcAttribute(): bool
    {
        return $this->service_type === 'amc_contract' || ! is_null($this->amc_plan_id);
    }

    public function getIsRunningContractAttribute(): bool
    {
        return $this->is_amc
            && $this->contract_status === 'active'
            && (! $this->contract_end_date || $this->contract_end_date->isFuture() || $this->contract_end_date->isToday());
    }

    public function getBillingCycleLabelAttribute(): string
    {
        return match ($this->billing_cycle) {
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'half_monthly' => 'Bi-Weekly (15 Days)',
            'quarterly' => 'Quarterly (3 Months)',
            'yearly' => 'Yearly',
            default => 'Monthly',
        };
    }

    public function getCollectedAmcRevenueAttribute(): float
    {
        return (float) $this->amcInvoices()->where('status', 'paid')->sum('amount');
    }

    public function getPendingAmcDueAttribute(): float
    {
        return (float) $this->amcInvoices()->whereIn('status', ['unpaid', 'overdue'])->sum('amount');
    }

    public function getDaysRemainingInContractAttribute(): ?int
    {
        if (! $this->contract_end_date) {
            return null;
        }

        return max(0, (int) today()->diffInDays($this->contract_end_date, false));
    }

    public static function divisionCode(?string $division, ?string $district = null): string
    {
        $div = strtolower(trim($division ?? ''));
        $dis = strtolower(trim($district ?? ''));

        if (str_contains($div, 'chatt') || str_contains($div, 'chitt') || str_contains($dis, 'chatt') || str_contains($dis, 'ctg')) {
            return 'CTG';
        }
        if (str_contains($div, 'sylh') || str_contains($dis, 'sylh')) {
            return 'SYL';
        }
        if (str_contains($div, 'raj') || str_contains($dis, 'raj')) {
            return 'RAJ';
        }
        if (str_contains($div, 'khul') || str_contains($dis, 'khul')) {
            return 'KHL';
        }
        if (str_contains($div, 'bari') || str_contains($dis, 'bari')) {
            return 'BAR';
        }
        if (str_contains($div, 'rang') || str_contains($dis, 'rang')) {
            return 'RNG';
        }
        if (str_contains($div, 'mymen') || str_contains($dis, 'mymen')) {
            return 'MYM';
        }

        return 'DHK';
    }

    public static function generateBookingNumber(?string $division = null, ?string $district = null): string
    {
        $prefix = static::divisionCode($division, $district);
        $count = static::where('booking_number', 'like', "{$prefix}-%")->count() + 1;

        return sprintf('%s-%05d', $prefix, $count);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending_review' => 'Pending',
            'accepted' => 'Accepted',
            'assigned' => 'Team Assigned',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function getAgreementUrlAttribute(): ?string
    {
        if (! $this->agreement_document_path) {
            return null;
        }

        if (str_starts_with($this->agreement_document_path, 'http://') || str_starts_with($this->agreement_document_path, 'https://')) {
            return $this->agreement_document_path;
        }

        return asset('storage/'.$this->agreement_document_path);
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
