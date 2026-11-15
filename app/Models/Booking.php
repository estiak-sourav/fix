<?php

namespace App\Models;

use Carbon\Carbon;
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
        'contract_tenure',
        'agreement_document_path',
        'amc_package_notes',
        'contract_status',
        'billing_cycle',
        'recurring_amount',
        'billing_day',
        'next_billing_date',
        'partner_id',
        'assigned_partner_crew_id',
        'arrival_window',
        'estimated_arrival_at',
        'partner_job_status',
        'partner_payout_amount',
        'partner_payout_status',
        'partner_payout_paid_at',
        'partner_payout_method',
        'partner_payout_reference',
        'partner_payout_notes',
        'partner_worked_hours',
        'partner_notes',
        'completion_photos',
        'partner_assigned_at',
        'payment_collected_by',
        'payment_method',
        'collected_amount',
        'payment_reference',
        'payment_collected_at',
        'cancellation_requested',
        'cancellation_requested_at',
        'cancellation_requested_by',
        'cancellation_request_reason',
        'cancellation_review_status',
        'cancellation_review_notes',
        'cancellation_reviewed_at',
        'cancellation_reviewed_by_admin_id',
        'crew_cancellation_reason',
        'crew_cancellation_requested_at',
    ];

    protected function casts(): array
    {
        return [
            'attached_images' => 'array',
            'completion_photos' => 'array',
            'partner_payout_amount' => 'decimal:2',
            'partner_worked_hours' => 'decimal:2',
            'partner_payout_paid_at' => 'datetime',
            'collected_amount' => 'decimal:2',
            'payment_collected_at' => 'datetime',
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
            'estimated_arrival_at' => 'datetime',
            'partner_assigned_at' => 'datetime',
            'cancellation_requested' => 'boolean',
            'cancellation_requested_at' => 'datetime',
            'cancellation_reviewed_at' => 'datetime',
            'crew_cancellation_requested_at' => 'datetime',
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

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function assignedCrew(): BelongsTo
    {
        return $this->belongsTo(PartnerCrew::class, 'assigned_partner_crew_id');
    }

    public function assignedPartnerCrew(): BelongsTo
    {
        return $this->assignedCrew();
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(BookingStatusLog::class)->latest('id');
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

    public function amcShifts(): HasMany
    {
        return $this->hasMany(AmcShift::class)->orderBy('scheduled_date')->orderBy('shift_number');
    }

    public function amcNotes(): HasMany
    {
        return $this->hasMany(AmcNote::class)->latest();
    }

    public function getPendingShiftsCountAttribute(): int
    {
        return $this->amcShifts()->whereIn('status', ['scheduled', 'on_the_way', 'in_progress'])->count();
    }

    public function getCompletedShiftsCountAttribute(): int
    {
        return $this->amcShifts()->where('status', 'completed')->count();
    }

    public function getCancelledShiftsCountAttribute(): int
    {
        return $this->amcShifts()->whereIn('status', ['cancelled', 'skipped'])->count();
    }

    public function getNextUpcomingShiftAttribute(): ?AmcShift
    {
        return $this->amcShifts()
            ->whereIn('status', ['scheduled', 'on_the_way', 'in_progress'])
            ->orderBy('scheduled_date')
            ->first();
    }

    /**
     * Generate scheduled routine maintenance shifts for this AMC contract.
     */
    public function generateAmcShifts(int $customCount = 0): int
    {
        if (! $this->is_amc) {
            return 0;
        }

        $startDate = $this->contract_start_date ? Carbon::parse($this->contract_start_date) : ($this->preferred_date ? Carbon::parse($this->preferred_date) : today());
        $endDate = $this->contract_end_date ? Carbon::parse($this->contract_end_date) : $startDate->copy()->addYear();
        $cycle = $this->billing_cycle ?: 'monthly';

        $dates = [];
        $current = $startDate->copy();

        if ($customCount > 0) {
            for ($i = 0; $i < $customCount; $i++) {
                $dates[] = $current->copy();
                match ($cycle) {
                    'weekly' => $current->addWeek(),
                    'half_monthly' => $current->addDays(15),
                    'quarterly' => $current->addMonths(3),
                    default => $current->addMonth(),
                };
            }
        } else {
            // Generate across contract tenure
            while ($current->lte($endDate) && count($dates) < 52) {
                $dates[] = $current->copy();
                match ($cycle) {
                    'weekly' => $current->addWeek(),
                    'half_monthly' => $current->addDays(15),
                    'quarterly' => $current->addMonths(3),
                    'yearly' => $current->addYear(),
                    default => $current->addMonth(),
                };
            }
        }

        if (empty($dates)) {
            $dates[] = $startDate->copy();
        }

        $created = 0;
        $existingCount = $this->amcShifts()->count();

        foreach ($dates as $idx => $date) {
            $shiftNum = $existingCount + $idx + 1;
            $exists = $this->amcShifts()->whereDate('scheduled_date', $date->toDateString())->exists();
            if ($exists) {
                continue;
            }

            $this->amcShifts()->create([
                'partner_id' => $this->partner_id,
                'assigned_partner_crew_id' => $this->assigned_partner_crew_id,
                'shift_number' => $shiftNum,
                'title' => "Routine Maintenance Shift #{$shiftNum}",
                'scheduled_date' => $date->toDateString(),
                'scheduled_time_slot' => $this->preferred_time_slot ?: 'Morning (9:00 AM - 12:00 PM)',
                'arrival_window' => $this->arrival_window ?: ($this->preferred_time_slot ?: '9:00 AM - 12:00 PM'),
                'status' => 'scheduled',
            ]);
            $created++;
        }

        return $created;
    }

    /**
     * Reassign all future pending shifts to a new partner/crew.
     */
    public function reassignAmcPartner(?int $newPartnerId): void
    {
        $this->update(['partner_id' => $newPartnerId]);
        $this->amcShifts()
            ->whereIn('status', ['scheduled', 'reassignment_needed'])
            ->update(['partner_id' => $newPartnerId]);
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
            'assigned' => 'Assigned',
            'on_the_way' => 'Crew on the way',
            'in_progress' => 'Working',
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
            'on_the_way' => '#9333ea', // Purple
            'in_progress' => '#06b6d4', // Cyan
            'completed' => '#10b981', // Green
            'cancelled' => '#ef4444', // Red
            default => '#6b7280',
        };
    }

    public function getCostTypeAttribute(): string
    {
        if ($this->final_cost > 0) {
            return 'final';
        }
        if ($this->estimated_cost > 0) {
            return 'estimated';
        }

        return 'none';
    }

    public function getIsPriceSetLaterAttribute(): bool
    {
        return empty($this->estimated_cost) && empty($this->final_cost);
    }

    public function getCancellationReasonAttribute(): ?string
    {
        if ($this->status !== 'cancelled') {
            return null;
        }

        $cancelLog = $this->relationLoaded('statusLogs')
            ? $this->statusLogs->firstWhere('to_status', 'cancelled')
            : $this->statusLogs()->where('to_status', 'cancelled')->latest('id')->first();

        if ($cancelLog && $cancelLog->comment) {
            return preg_replace('/^(Order cancelled|Service request cancelled by client|Request cancelled by client|Cancelled by client):\s*/i', '', $cancelLog->comment);
        }

        return null;
    }

    public function canBeCancelledByCustomer(): bool
    {
        return $this->status === 'pending_review' && $this->accepted_at === null;
    }

    public function getCanBeCancelledByCustomerAttribute(): bool
    {
        return $this->canBeCancelledByCustomer();
    }
}
