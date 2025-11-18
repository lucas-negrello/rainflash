<?php

namespace App\Enums;

enum TimeEntryOriginEnum: int
{
    case MANUAL = 0;
    case COUNTER = 1;
    case EXTERNAL = 2;

    public function label(): string
    {
        return match ($this) {
            self::MANUAL => 'Manual',
            self::COUNTER => 'Timer',
            self::EXTERNAL => 'Externo',
        };
    }
}
