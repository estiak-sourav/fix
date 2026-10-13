<?php

namespace App\Enums;

enum BookingStatus: string
{
    case PENDING_REVIEW = 'pending_review';
    case CONFIRMED = 'confirmed';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING_REVIEW => 'Pending Review',
            self::CONFIRMED => 'Confirmed',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PENDING_REVIEW => 'bg-amber-100 text-amber-800 border-amber-200',
            self::CONFIRMED => 'bg-blue-100 text-blue-800 border-blue-200',
            self::IN_PROGRESS => 'bg-purple-100 text-purple-800 border-purple-200',
            self::COMPLETED => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            self::CANCELLED => 'bg-rose-100 text-rose-800 border-rose-200',
        };
    }
}
