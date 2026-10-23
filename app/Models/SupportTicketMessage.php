<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicketMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'support_ticket_id',
        'sender_type',
        'sender_id',
        'sender_name',
        'message',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }

    public function isFromCustomer(): bool
    {
        return $this->sender_type === 'customer';
    }

    public function isFromAdmin(): bool
    {
        return $this->sender_type === 'admin';
    }
}
