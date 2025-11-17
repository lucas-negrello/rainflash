<?php

namespace App\Enums;

enum CompanySubscriptionStatusEnum: int
{
    case CANCELLED = 0;
    case ACTIVE = 1;
    case PAST_DUE = 2;
    case TRIAL = 3;

    public function label()
    {
        return match ($this) {
            self::CANCELLED => 'Cancelado',
            self::ACTIVE => 'Ativo',
            self::PAST_DUE => 'Vencido',
            self::TRIAL => 'Trial',
        };
    }
}
