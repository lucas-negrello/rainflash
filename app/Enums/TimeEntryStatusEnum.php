<?php

namespace App\Enums;

enum TimeEntryStatusEnum: int
{
    case PENDING = 0;
    case APPROVED = 1;
    case REPROVED = 2;

    public function label()
    {
        return match ($this) {
            self::PENDING => 'Pendente',
            self::APPROVED => 'Aprovado',
            self::REPROVED => 'Reprovado',
        };
    }
}
