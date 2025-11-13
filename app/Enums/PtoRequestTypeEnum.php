<?php

namespace App\Enums;

enum PtoRequestTypeEnum: int
{
    case VACATION = 0;
    case SICKNESS = 1;
    case PTO = 2;
    case UNPAID = 3;
    case OTHER = 4;

    public function label(): string
    {
        return match ($this) {
            self::VACATION => 'Férias',
            self::SICKNESS => 'Doença',
            self::PTO => 'PTO',
            self::UNPAID => 'Não Remunerado',
            self::OTHER => 'Outro',
        };
    }
}
