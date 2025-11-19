<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum PtoRequestHoursOptionEnum: int implements TableEnumInterface
{
    use HasTableEnum;

    case FULL_DAY = 0;
    case HALF_DAY = 1;
    case CUSTOM_HOURS = 2;

    public function label(): string
    {
        return match ($this) {
            self::FULL_DAY => 'Dia Inteiro',
            self::HALF_DAY => 'Meio Dia',
            self::CUSTOM_HOURS => 'Horas Personalizadas',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::FULL_DAY => 'blue',
            self::HALF_DAY => 'yellow',
            self::CUSTOM_HOURS => 'purple',
        };
    }
}
