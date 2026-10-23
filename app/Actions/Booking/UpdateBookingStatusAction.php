<?php

namespace App\Actions\Booking;

use App\Models\Admin;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateBookingStatusAction
{
    /**
     * Update booking lifecycle status with audit logging.
     *
     * @param  array{
     *     status: string,
     *     comment?: string|null,
     *     note?: string|null,
     *     assigned_team?: string|null,
     *     estimated_cost?: float|int|null,
     *     final_cost?: float|int|null,
     *     admin_notes?: string|null
     * } $data
     */
    public function execute(Booking $booking, array $data, ?Admin $admin = null, ?User $user = null): Booking
    {
        return DB::transaction(function () use ($booking, $data, $admin, $user) {
            $oldStatus = $booking->status;
            $newStatus = $data['status'];

            $updateData = [
                'status' => $newStatus,
                'admin_notes' => $data['admin_notes'] ?? ($data['note'] ?? $booking->admin_notes),
                'assigned_team' => $data['assigned_team'] ?? $booking->assigned_team,
            ];

            if (isset($data['estimated_cost'])) {
                $updateData['estimated_cost'] = $data['estimated_cost'];
            }
            if (isset($data['final_cost'])) {
                $updateData['final_cost'] = $data['final_cost'];
            }

            if ($newStatus === 'accepted' && ! $booking->accepted_at) {
                $updateData['accepted_at'] = now();
            }
            if ($newStatus === 'completed' && ! $booking->completed_at) {
                $updateData['completed_at'] = now();
            }
            if ($newStatus === 'cancelled' && ! $booking->cancelled_at) {
                $updateData['cancelled_at'] = now();
            }

            $booking->update($updateData);

            $comment = $data['comment'] ?? ($data['note'] ?? 'Status updated to '.ucfirst(str_replace('_', ' ', $newStatus)));

            BookingStatusLog::create([
                'booking_id' => $booking->id,
                'changed_by_user_id' => $user?->id,
                'changed_by_admin_id' => $admin?->id,
                'from_status' => $oldStatus,
                'to_status' => $newStatus,
                'comment' => $comment,
                'is_customer_visible' => true,
            ]);

            return $booking;
        });
    }
}
