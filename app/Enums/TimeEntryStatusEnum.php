<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum TimeEntryStatusEnum: int implements TableEnumInterface
{
    use HasTableEnum;

    case PENDING = 0;
    case APPROVED = 1;
    case REPROVED = 2;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pendente',
            self::APPROVED => 'Aprovado',
            self::REPROVED => 'Reprovado',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'yellow',
            self::APPROVED => 'green',
            self::REPROVED => 'red',
        };
    }
}
