<?php

namespace App\Enums;

enum AdminRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case OPERATIONS_MANAGER = 'operations_manager';
    case SUPPORT = 'support';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Administrator',
            self::OPERATIONS_MANAGER => 'Operations Manager',
            self::SUPPORT => 'Support Staff',
        };
    }
}
