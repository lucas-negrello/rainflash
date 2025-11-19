<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum ProjectTypeEnum: int implements TableEnumInterface
{
    use HasTableEnum;

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

    public function color(): string
    {
        return match ($this) {
            self::PRODUCT => 'blue',
            self::OUTSOURCING => 'purple',
            self::CLIENT_INTERNAL => 'gray',
        };
    }
}
