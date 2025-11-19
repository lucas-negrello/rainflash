<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum CalendarEventTypeEnum: int implements TableEnumInterface
{
    use HasTableEnum;

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

    public function color(): string
    {
        return match ($this) {
            self::HOLIDAY => 'green',
            self::COMPANY_EVENT => 'blue',
            self::BLOCK => 'red',
        };
    }
}
