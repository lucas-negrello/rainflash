<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum CompanySubscriptionStatusEnum: int implements TableEnumInterface
{
    use HasTableEnum;

    case CANCELLED = 0;
    case ACTIVE = 1;
    case PAST_DUE = 2;
    case TRIAL = 3;

    public function label(): string
    {
        return match ($this) {
            self::CANCELLED => 'Cancelado',
            self::ACTIVE => 'Ativo',
            self::PAST_DUE => 'Vencido',
            self::TRIAL => 'Trial',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::CANCELLED => 'danger',
            self::ACTIVE => 'success',
            self::PAST_DUE => 'warning',
            self::TRIAL => 'secondary',
        };
    }
}
