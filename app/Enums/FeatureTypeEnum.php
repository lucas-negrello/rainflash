<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum FeatureTypeEnum: int implements TableEnumInterface
{
    use HasTableEnum;

    case BOOLEAN = 0;
    case LIMIT = 1;
    case TIER = 2;

    public function label(): string
    {
        return match ($this) {
            self::BOOLEAN => 'Boleano',
            self::LIMIT => 'Limite',
            self::TIER => 'Nível',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::BOOLEAN => 'primary',
            self::LIMIT => 'secondary',
            self::TIER => 'warning',
        };
    }
}
