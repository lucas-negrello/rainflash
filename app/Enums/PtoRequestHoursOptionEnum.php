<?php

namespace App\Enums;

enum PtoRequestHoursOptionEnum: int
{
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
}
