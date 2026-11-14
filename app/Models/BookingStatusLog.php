<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingStatusLog extends Model
{
    protected $fillable = [
        'booking_id',
        'changed_by_user_id',
        'changed_by_admin_id',
        'from_status',
        'to_status',
        'comment',
        'is_customer_visible',
    ];

    protected function casts(): array
    {
        return [
            'is_customer_visible' => 'boolean',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }

    public function changedByAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'changed_by_admin_id');
    }

    /**
     * Get sanitized, customer-safe comment that never exposes partner/crew names or internal dispatch notes.
     */
    public function getCustomerCommentAttribute(): string
    {
        $comment = $this->comment ?: 'Status updated to '.ucfirst(str_replace('_', ' ', $this->to_status));

        // Sanitize partner / crew statements
        $clean = preg_replace("/Partner\s+'[^']*'\s*accepted the job assignment\.?/i", 'Work order confirmed and scheduled for service.', $comment);
        $clean = preg_replace("/(?:Technician\/Crew|Crew|Technician)\s+from\s+'[^']*'\s*is currently on the way/i", 'Technician team is on the way', $clean);
        $clean = preg_replace("/Service work (started|completed) on site by\s+'[^']*'\.?/i", 'Service work $1 on site.', $clean);
        $clean = preg_replace("/Service work successfully completed by\s+'[^']*'\.?/i", 'Service work successfully completed.', $clean);
        $clean = preg_replace("/\s+by\s+'[^']*'\.?/i", '.', $clean);
        $clean = preg_replace("/\s+by\s+[A-Za-z0-9\s_-]+(?=\.|$)/i", '', $clean);
        $clean = preg_replace("/Partner\s+'[^']*'/i", 'Technician team', $clean);

        return trim($clean);
    }
}
