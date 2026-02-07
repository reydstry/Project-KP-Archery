<?php

namespace App\Enums;

enum StatusMember: string
{
    case STATUS_PENDING = 'pending';
    case STATUS_ACTIVE = 'active';
    case STATUS_INACTIVE = 'inactive';

    public function label(): string
    {
        return match($this) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
        };
    }
}
