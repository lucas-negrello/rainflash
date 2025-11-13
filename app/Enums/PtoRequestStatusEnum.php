<?php

namespace App\Enums;

enum PtoRequestStatusEnum: int
{
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
}
