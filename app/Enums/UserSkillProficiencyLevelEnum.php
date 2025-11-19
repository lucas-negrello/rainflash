<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum UserSkillProficiencyLevelEnum: int implements TableEnumInterface
{
    use HasTableEnum;

    case VERY_LOW = 1;
    case LOW = 2;
    case MEDIUM = 3;
    case HIGH = 4;
    case VERY_HIGH = 5;

    public function label(): string
    {
        return match ($this) {
            self::VERY_LOW => 'Muito Baixa',
            self::LOW => 'Baixa',
            self::MEDIUM => 'Média',
            self::HIGH => 'Alta',
            self::VERY_HIGH => 'Muito Alta',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::VERY_LOW => 'red',
            self::LOW => 'orange',
            self::MEDIUM => 'yellow',
            self::HIGH => 'blue',
            self::VERY_HIGH => 'green',
        };
    }
}
