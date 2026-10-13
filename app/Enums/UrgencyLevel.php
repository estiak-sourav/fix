<?php

namespace App\Enums;

enum UrgencyLevel: string
{
    case EMERGENCY_1H = 'emergency_1h';
    case SAME_DAY_4H = 'same_day_4h';
    case SCHEDULED = 'scheduled';

    public function label(): string
    {
        return match ($this) {
            self::EMERGENCY_1H => 'Emergency (Within 60 Minutes)',
            self::SAME_DAY_4H => 'Same-Day Urgent (Within 4 Hours)',
            self::SCHEDULED => 'Standard Scheduled Slot',
        };
    }

    public function isEmergency(): bool
    {
        return $this === self::EMERGENCY_1H;
    }
}
