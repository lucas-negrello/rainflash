<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum PermissionScopeEnum: int implements TableEnumInterface
{
    use HasTableEnum;

    case ROOT = 0;
    case USER = 1;

    public function label(): string
    {
        return match ($this) {
            self::ROOT => 'Administrador',
            self::USER => 'Usuário',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ROOT => 'primary',
            self::USER => 'success',
        };
    }
}
