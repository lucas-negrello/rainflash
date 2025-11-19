<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum PtoRequestTypeEnum: int implements TableEnumInterface
{
    use HasTableEnum;

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

    public function color(): string
    {
        return match ($this) {
            self::VACATION => 'green',
            self::SICKNESS => 'red',
            self::PTO => 'blue',
            self::UNPAID => 'gray',
            self::OTHER => 'purple',
        };
    }
}
