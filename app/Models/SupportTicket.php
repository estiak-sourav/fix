<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'admin_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'subject',
        'priority',
        'status',
        'created_by',
        'access_token',
        'last_reply_at',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'last_reply_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportTicketMessage::class)->orderBy('id', 'asc');
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    public static function generateTicketNumber(): string
    {
        $prefix = 'FIX-TK-';
        $random = strtoupper(Str::random(5));
        $number = $prefix.now()->format('ym').'-'.$random;

        while (self::where('ticket_number', $number)->exists()) {
            $random = strtoupper(Str::random(5));
            $number = $prefix.now()->format('ym').'-'.$random;
        }

        return $number;
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'open' => 'badge-info',
            'in_progress' => 'badge-warning',
            'closed' => 'badge-secondary',
            default => 'badge-light',
        };
    }

    public function priorityBadgeClass(): string
    {
        return match ($this->priority) {
            'urgent' => 'badge-danger',
            'high' => 'badge-warning',
            'low' => 'badge-light',
            default => 'badge-primary',
        };
    }
}
