<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum ProjectStatusEnum: int implements TableEnumInterface
{
    use HasTableEnum;

    case INACTIVE = 0;
    case ACTIVE = 1;
    case PENDING = 2;
    case COMPLETED = 3;

    public function label(): string
    {
        return match ($this) {
            self::INACTIVE => 'Inativo',
            self::ACTIVE => 'Ativo',
            self::PENDING => 'Pendente',
            self::COMPLETED => 'Completo',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::INACTIVE => 'gray',
            self::ACTIVE => 'green',
            self::PENDING => 'yellow',
            self::COMPLETED => 'blue',
        };
    }
}
