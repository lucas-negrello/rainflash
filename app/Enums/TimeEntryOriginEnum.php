<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum TimeEntryOriginEnum: int implements TableEnumInterface
{
    use HasTableEnum;

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

    public function color(): string
    {
        return match ($this) {
            self::MANUAL => 'gray',
            self::COUNTER => 'blue',
            self::EXTERNAL => 'purple',
        };
    }
}
