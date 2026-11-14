<?php

namespace App\Services;

use App\Mail\AdminEventNotificationMail;
use App\Mail\PartnerJobAssignedMail;
use App\Mail\PartnerJobCancelledMail;
use App\Mail\PartnerJobUpdatedMail;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\Partner;
use App\Models\PartnerNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class NotificationDispatcher
{
    /**
     * Dispatch in-app & email notification to assigned partner.
     */
    public static function notifyPartnerJobAssigned(Booking $booking): void
    {
        $partner = $booking->partner;
        if (! $partner instanceof Partner) {
            return;
        }

        // 1. Create In-App Notification for Vendor Portal
        try {
            $serviceTitle = $booking->service?->title ?? $booking->service?->name ?? $booking->amcPlan?->name ?? 'Facility Service';
            $dateStr = $booking->preferred_date ? $booking->preferred_date->format('M d, Y') : 'Immediate';
            $slotStr = $booking->arrival_window ?: ($booking->preferred_time_slot ?: 'Standard');

            PartnerNotification::create([
                'partner_id' => $partner->id,
                'booking_id' => $booking->id,
                'type' => 'job_assigned',
                'title' => "New Job Assigned: #{$booking->booking_number}",
                'message' => "{$serviceTitle} at {$booking->area}. Scheduled for {$dateStr} ({$slotStr}).",
                'action_url' => route('partner.jobs.show', $booking),
            ]);
        } catch (Throwable $e) {
            Log::warning("Failed creating in-app notification for partner {$partner->id}: ".$e->getMessage());
        }

        // 2. Dispatch Email if email is configured and preferences allow
        if (empty($partner->email) || ! $partner->wantsNotification('job_assigned')) {
            return;
        }

        static::executeDeferred(function () use ($partner, $booking) {
            try {
                Mail::to($partner->email)->send(new PartnerJobAssignedMail($partner, $booking));
            } catch (Throwable $e) {
                Log::warning("Failed sending partner job assigned email to {$partner->email}: ".$e->getMessage());
            }
        });
    }

    /**
     * Dispatch schedule/arrival update notice to partner.
     */
    public static function notifyPartnerJobUpdated(Booking $booking, string $updateNote = 'Job schedule or arrival window updated'): void
    {
        $partner = $booking->partner;
        if (! $partner instanceof Partner) {
            return;
        }

        // 1. Create In-App Notification
        try {
            PartnerNotification::create([
                'partner_id' => $partner->id,
                'booking_id' => $booking->id,
                'type' => 'job_updated',
                'title' => "Job #{$booking->booking_number} Updated",
                'message' => $updateNote,
                'action_url' => route('partner.jobs.show', $booking),
            ]);
        } catch (Throwable $e) {
            Log::warning("Failed creating in-app notification for partner {$partner->id}: ".$e->getMessage());
        }

        // 2. Dispatch Email
        if (empty($partner->email) || ! $partner->wantsNotification('job_updated')) {
            return;
        }

        static::executeDeferred(function () use ($partner, $booking, $updateNote) {
            try {
                Mail::to($partner->email)->send(new PartnerJobUpdatedMail($partner, $booking, $updateNote));
            } catch (Throwable $e) {
                Log::warning("Failed sending partner job updated email to {$partner->email}: ".$e->getMessage());
            }
        });
    }

    /**
     * Dispatch job cancellation notice to partner.
     */
    public static function notifyPartnerJobCancelled(Booking $booking, string $reason = 'Order cancelled by client or dispatch'): void
    {
        $partner = $booking->partner;
        if (! $partner instanceof Partner) {
            return;
        }

        // 1. Create In-App Notification
        try {
            PartnerNotification::create([
                'partner_id' => $partner->id,
                'booking_id' => $booking->id,
                'type' => 'job_cancelled',
                'title' => "Job #{$booking->booking_number} Cancelled",
                'message' => $reason,
                'action_url' => route('partner.jobs.show', $booking),
            ]);
        } catch (Throwable $e) {
            Log::warning("Failed creating in-app notification for partner {$partner->id}: ".$e->getMessage());
        }

        // 2. Dispatch Email
        if (empty($partner->email) || ! $partner->wantsNotification('job_cancelled')) {
            return;
        }

        static::executeDeferred(function () use ($partner, $booking, $reason) {
            try {
                Mail::to($partner->email)->send(new PartnerJobCancelledMail($partner, $booking, $reason));
            } catch (Throwable $e) {
                Log::warning("Failed sending partner job cancelled email to {$partner->email}: ".$e->getMessage());
            }
        });
    }

    /**
     * Dispatch account approval in-app notification to partner.
     */
    public static function notifyPartnerAccountApproved(Partner $partner): void
    {
        try {
            PartnerNotification::create([
                'partner_id' => $partner->id,
                'type' => 'account_approved',
                'title' => 'Account Approved & Active',
                'message' => 'Congratulations! Your partner account is approved and you can now receive field service work orders.',
                'action_url' => route('partner.dashboard'),
            ]);
        } catch (Throwable $e) {
            Log::warning("Failed creating approval in-app notification for partner {$partner->id}: ".$e->getMessage());
        }
    }

    /**
     * Dispatch CMS operational alert to Super Admin and relevant staff based on section subscription.
     * Sections: 'orders', 'partners', 'support', 'payments'.
     */
    public static function notifyAdminSection(
        string $section,
        string $title,
        string $message,
        ?string $actionUrl = null,
        array $details = []
    ): void {
        $recipients = Admin::where('is_active', true)
            ->get()
            ->filter(fn (Admin $admin) => $admin->wantsEmailFor($section));

        if ($recipients->isEmpty()) {
            return;
        }

        static::executeDeferred(function () use ($recipients, $section, $title, $message, $actionUrl, $details) {
            foreach ($recipients as $admin) {
                try {
                    Mail::to($admin->email)->send(
                        new AdminEventNotificationMail($section, $title, $message, $actionUrl, $details)
                    );
                } catch (Throwable $e) {
                    Log::warning("Failed dispatching admin {$section} notification to {$admin->email}: ".$e->getMessage());
                }
            }
        });
    }

    /**
     * Execute callback after response during HTTP requests, or synchronously during unit tests.
     */
    protected static function executeDeferred(callable $callback): void
    {
        if (app()->runningUnitTests()) {
            $callback();
        } else {
            defer($callback);
        }
    }
}
