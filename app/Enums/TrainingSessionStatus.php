<?php

namespace App\Enums;

enum TrainingSessionStatus: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';
    case CANCELED = 'canceled';

    public function label(): string
    {
        return match($this) {
            self::OPEN => 'Open',
            self::CLOSED => 'Closed',
            self::CANCELED => 'Canceled',
        };
    }
}
