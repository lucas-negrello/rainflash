<?php

namespace App\Enums;

enum CalendarEventTypeEnum: int
{
    case HOLIDAY = 0;
    case COMPANY_EVENT = 1;
    case BLOCK = 2;

    public function label(): string
    {
        return match ($this) {
            self::HOLIDAY => 'Feriado',
            self::COMPANY_EVENT => 'Evento da Empresa',
            self::BLOCK => 'Bloqueio',
        };
    }
}
