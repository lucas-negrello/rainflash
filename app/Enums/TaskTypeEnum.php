<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum TaskTypeEnum: int implements TableEnumInterface
{
    use HasTableEnum;

    case SUPPORT = 0;
    case FEATURE = 1;
    case TECH = 2;
    case BUG = 3;
    case OTHER = 99;

    public function label(): string
    {
        return match ($this) {
            self::SUPPORT => 'Suporte',
            self::FEATURE => 'Feature',
            self::TECH => 'Tech',
            self::BUG => 'Bug',
            self::OTHER => 'Other',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SUPPORT => 'gray',
            self::FEATURE => 'blue',
            self::TECH => 'purple',
            self::BUG => 'red',
            self::OTHER => 'yellow',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::SUPPORT => 'heroicon-o-lifebuoy',
            self::FEATURE => 'heroicon-o-sparkles',
            self::TECH => 'heroicon-o-code-bracket',
            self::BUG => 'heroicon-o-bug-ant',
            self::OTHER => 'heroicon-o-ellipsis-horizontal-circle',
        };
    }
}
