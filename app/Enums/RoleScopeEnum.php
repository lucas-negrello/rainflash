<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum RoleScopeEnum: int implements TableEnumInterface
{
    use HasTableEnum;

    case GLOBAL = 0;
    case COMPANY = 1;

    public function label(): string
    {
        return match ($this) {
            self::GLOBAL => 'Global',
            self::COMPANY => 'Empresa',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::GLOBAL => 'purple',
            self::COMPANY => 'blue',
        };
    }
}
