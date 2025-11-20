<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum FeatureTierOptionsEnum: int implements TableEnumInterface
{
    use HasTableEnum;

    case BASIC = 0;
    case STANDARD = 1;
    case PREMIUM = 2;
    case FULL = 3;

    public function label(): string
    {
        return match ($this) {
            self::BASIC => 'Básico',
            self::STANDARD => 'Padrão',
            self::PREMIUM => 'Premium',
            self::FULL => 'Completo',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::BASIC => 'gray',
            self::STANDARD => 'warning',
            self::PREMIUM => 'secondary',
            self::FULL => 'primary',
        };
    }
}
