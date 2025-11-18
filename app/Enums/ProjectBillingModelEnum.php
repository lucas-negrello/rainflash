<?php

namespace App\Enums;

enum ProjectBillingModelEnum: int
{
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
}
