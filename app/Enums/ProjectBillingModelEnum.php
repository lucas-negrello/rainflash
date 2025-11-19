<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum ProjectBillingModelEnum: int implements TableEnumInterface
{
    use HasTableEnum;

    case TNM = 0;
    case FIXED = 1;
    case INTERNAL = 99;

    public function label(): string
    {
        return match ($this) {
            self::TNM => 'Tempo e Recursos',
            self::FIXED => 'Preço Fixo',
            self::INTERNAL => 'Interno',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::TNM => 'blue',
            self::FIXED => 'purple',
            self::INTERNAL => 'gray',
        };
    }
}
