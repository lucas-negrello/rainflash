<?php

namespace App\Enums;

enum TaskTypeEnum: int
{
    case SUPPORT = 0;
    case FEATURE = 1;
    case TECH = 2;
    case BUG = 3;
    case OTHER = 99;

    public function label(): string
    {
        return match ($this) {
            TaskTypeEnum::SUPPORT => 'Suporte',
            TaskTypeEnum::FEATURE => 'Feature',
            TaskTypeEnum::TECH => 'Tech',
            TaskTypeEnum::BUG => 'Bug',
            TaskTypeEnum::OTHER => 'Other',
        };
    }
}
