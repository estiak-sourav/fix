<?php

namespace App\Actions\Booking;

use App\Models\AdminNotification;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class CreateBookingAction
{
    /**
     * Create a new facility service booking and trigger notifications.
     *
     * @param  array<string, mixed>  $data
     * @param  array<UploadedFile>|UploadedFile|null  $problemPhotos
     */
    public function execute(array $data, User $user, mixed $problemPhotos = null): Booking
    {
        $attachedImages = [];
        if ($problemPhotos instanceof UploadedFile) {
            $path = $problemPhotos->store('bookings', 'public');
            $attachedImages[] = '/storage/'.$path;
        } elseif (is_array($problemPhotos)) {
            foreach ($problemPhotos as $photo) {
                if ($photo instanceof UploadedFile) {
                    $path = $photo->store('bookings', 'public');
                    $attachedImages[] = '/storage/'.$path;
                }
            }
        }

        return DB::transaction(function () use ($data, $user, $attachedImages) {
            $bookingNumber = Booking::generateBookingNumber($data['division'] ?? null, $data['district'] ?? null);

            $isAmc = ($data['service_type'] ?? '') === 'amc_contract' || ! empty($data['amc_plan_id']);
            $contractTenure = $data['contract_tenure'] ?? ($isAmc ? '1 Year' : null);
            $startDate = null;
            $endDate = null;

            if ($isAmc) {
                $startDate = ! empty($data['preferred_date']) ? Carbon::parse($data['preferred_date']) : today();
                $endDate = match ($contractTenure) {
                    '3 Months', '3_months' => $startDate->copy()->addMonths(3),
                    '6 Months', '6_months' => $startDate->copy()->addMonths(6),
                    '2 Years', '2_years' => $startDate->copy()->addYears(2),
                    '3 Years', '3_years' => $startDate->copy()->addYears(3),
                    default => $startDate->copy()->addYear(),
                };
            }

            $booking = Booking::create([
                'booking_number' => $bookingNumber,
                'user_id' => $user->id,
                'service_id' => $data['service_id'] ?? null,
                'amc_plan_id' => $data['amc_plan_id'] ?? null,
                'service_type' => $data['service_type'],
                'contract_tenure' => $contractTenure,
                'contract_start_date' => $startDate ? $startDate->toDateString() : null,
                'contract_end_date' => $endDate ? $endDate->toDateString() : null,
                'customer_name' => $data['customer_name'],
                'customer_email' => $user->email,
                'customer_phone' => $data['customer_phone'],
                'company_name' => $data['company_name'] ?? $user->company_name,
                'property_type' => $data['property_type'],
                'estimated_sqft' => $data['estimated_sqft'] ?? null,
                'division' => $data['division'],
                'district' => $data['district'],
                'area' => $data['area'],
                'street_address' => $data['street_address'],
                'preferred_date' => $data['preferred_date'],
                'preferred_time_slot' => $data['preferred_time_slot'],
                'urgency' => $data['urgency'],
                'problem_description' => ! empty($data['problem_description']) ? $data['problem_description'] : ($contractTenure ? "Annual Maintenance Contract ({$contractTenure}) request." : 'Facility maintenance request.'),
                'attached_images' => ! empty($attachedImages) ? $attachedImages : null,
                'status' => 'pending_review',
            ]);

            // Audit timeline entry
            BookingStatusLog::create([
                'booking_id' => $booking->id,
                'changed_by_user_id' => $user->id,
                'from_status' => 'initiated',
                'to_status' => 'pending_review',
                'comment' => 'Service request registered via customer portal.',
                'is_customer_visible' => true,
            ]);

            // Create audible admin alert notification
            $serviceTitle = $booking->service?->title ?? ($booking->amcPlan?->name ?? 'Custom Service');
            AdminNotification::create([
                'type' => $booking->urgency === 'emergency_1h' ? 'emergency_alert' : 'new_booking',
                'title' => "New Booking: #{$booking->booking_number}",
                'message' => "{$booking->customer_name} submitted a request for [{$serviceTitle}] in {$booking->area}.",
                'booking_id' => $booking->id,
                'action_url' => "/admin/bookings/{$booking->id}",
                'is_read' => false,
                'sound_played' => false,
            ]);

            // Update user default contact fields if empty (address is kept separate from profile)
            $user->update([
                'phone' => $user->phone ?: $data['customer_phone'],
                'company_name' => $user->company_name ?: ($data['company_name'] ?? null),
            ]);

            return $booking;
        });
    }
}
