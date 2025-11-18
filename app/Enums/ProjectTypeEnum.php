<?php

namespace App\Enums;

enum ProjectTypeEnum: int
{
    case PRODUCT = 0;
    case OUTSOURCING = 1;
    case CLIENT_INTERNAL = 99;

    public function label(): string
    {
        return match ($this) {
            self::PRODUCT => 'Produto',
            self::OUTSOURCING => 'Outsourcing',
            self::CLIENT_INTERNAL => 'Interno do Cliente',
        };
    }
}
