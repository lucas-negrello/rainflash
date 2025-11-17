<?php

namespace App\Enums;

enum FeatureTypeEnum: int
{
    case BOOLEAN = 0;
    case LIMIT = 1;
    case TIER = 2;

    public function label()
    {
        return match ($this) {
            self::BOOLEAN => 'Boleano',
            self::LIMIT => 'Limite',
            self::TIER => 'Nível',
        };
    }
}
