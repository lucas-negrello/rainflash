<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum CalendarScopeEnum: int implements TableEnumInterface
{
    use HasTableEnum;

    case COMPANY = 0;
    case REGIONAL = 1;
    case USER = 2;

    public function label(): string
    {
        return match ($this) {
            self::COMPANY => 'Empresarial',
            self::REGIONAL => 'Regional',
            self::USER => 'Particular',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::COMPANY => 'blue',
            self::REGIONAL => 'purple',
            self::USER => 'teal',
        };
    }
}
