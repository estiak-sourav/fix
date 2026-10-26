<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\AdminActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class ActivityLogService
{
    /**
     * Record an administrative activity log entry.
     *
     * @param  array<string, mixed>  $properties
     */
    public static function log(
        string $category,
        string $action,
        string $description,
        ?Model $subject = null,
        array $properties = [],
        ?Admin $admin = null
    ): ?AdminActivityLog {
        try {
            $actor = $admin ?? Auth::guard('admin')->user();

            $ipAddress = request()?->ip();
            $userAgent = request()?->userAgent();

            return AdminActivityLog::create([
                'admin_id' => $actor?->id,
                'admin_name' => $actor?->name ?? 'System / Anonymous',
                'admin_email' => $actor?->email,
                'admin_role' => $actor?->role,
                'category' => $category,
                'action' => $action,
                'description' => $description,
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id' => $subject?->getKey(),
                'properties' => $properties,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
            ]);
        } catch (Throwable $e) {
            Log::warning('Failed to record admin activity log: '.$e->getMessage(), [
                'category' => $category,
                'action' => $action,
                'description' => $description,
            ]);

            return null;
        }
    }

    /**
     * Log authentication events (login, logout, password change).
     *
     * @param  array<string, mixed>  $properties
     */
    public static function logAuth(string $action, string $description, ?Admin $admin = null, array $properties = []): ?AdminActivityLog
    {
        return self::log('auth', $action, $description, $admin, $properties, $admin);
    }

    /**
     * Log staff and role permissions management events.
     *
     * @param  array<string, mixed>  $properties
     */
    public static function logStaff(string $action, string $description, ?Admin $targetStaff = null, array $properties = []): ?AdminActivityLog
    {
        return self::log('staff', $action, $description, $targetStaff, $properties);
    }

    /**
     * Log work orders and booking workflow updates.
     *
     * @param  array<string, mixed>  $properties
     */
    public static function logBooking(string $action, string $description, Model $booking, array $properties = []): ?AdminActivityLog
    {
        return self::log('bookings', $action, $description, $booking, $properties);
    }

    /**
     * Log support tickets desk actions.
     *
     * @param  array<string, mixed>  $properties
     */
    public static function logTicket(string $action, string $description, Model $ticket, array $properties = []): ?AdminActivityLog
    {
        return self::log('tickets', $action, $description, $ticket, $properties);
    }

    /**
     * Log client user account actions.
     *
     * @param  array<string, mixed>  $properties
     */
    public static function logUser(string $action, string $description, Model $user, array $properties = []): ?AdminActivityLog
    {
        return self::log('users', $action, $description, $user, $properties);
    }

    /**
     * Log site settings and gateway adjustments.
     *
     * @param  array<string, mixed>  $properties
     */
    public static function logSettings(string $action, string $description, array $properties = []): ?AdminActivityLog
    {
        return self::log('settings', $action, $description, null, $properties);
    }

    /**
     * Log service catalog or AMC package changes.
     *
     * @param  array<string, mixed>  $properties
     */
    public static function logService(string $action, string $description, ?Model $service = null, array $properties = []): ?AdminActivityLog
    {
        return self::log('services', $action, $description, $service, $properties);
    }

    /**
     * Log web content modifications (Hero, FAQs, Testimonials).
     *
     * @param  array<string, mixed>  $properties
     */
    public static function logContent(string $action, string $description, ?Model $content = null, array $properties = []): ?AdminActivityLog
    {
        return self::log('content', $action, $description, $content, $properties);
    }

    /**
     * Log partners & vendor network events.
     *
     * @param  array<string, mixed>  $properties
     */
    public static function logPartner(string $action, string $description, ?Model $partner = null, array $properties = []): ?AdminActivityLog
    {
        return self::log('partners', $action, $description, $partner, $properties);
    }

    /**
     * Log general or system level events.
     *
     * @param  array<string, mixed>  $properties
     */
    public static function logSystem(string $action, string $description, ?Model $subject = null, array $properties = []): ?AdminActivityLog
    {
        return self::log('system', $action, $description, $subject, $properties);
    }
}
