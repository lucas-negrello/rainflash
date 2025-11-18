<?php

namespace App\Enums;

enum RoleScopeEnum: int
{
    case GLOBAL = 0;
    case COMPANY = 1;

    public function label()
    {
        return match ($this) {
            RoleScopeEnum::GLOBAL => 'Global',
            RoleScopeEnum::COMPANY => 'Empresa',
        };
    }

    public static function labels()
    {
        return [
            self::GLOBAL->value => self::GLOBAL->label(),
            self::COMPANY->value => self::COMPANY->label(),
        ];
    }
}
