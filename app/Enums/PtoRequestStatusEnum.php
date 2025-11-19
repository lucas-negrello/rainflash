<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum PtoRequestStatusEnum: int implements TableEnumInterface
{
    use HasTableEnum;

    case REQUESTED = 0;
    case APPROVED = 1;
    case REPROVED = 2;
    case CANCELED = 3;

    public function label(): string
    {
        return match ($this) {
            self::REQUESTED => 'Solicitado',
            self::APPROVED => 'Aprovado',
            self::REPROVED => 'Rejeitado',
            self::CANCELED => 'Cancelado',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::REQUESTED => 'blue',
            self::APPROVED => 'green',
            self::REPROVED => 'red',
            self::CANCELED => 'gray',
        };
    }
}
