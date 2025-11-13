<?php

namespace App\Enums;

enum CalendarScopeEnum: int
{
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
}
