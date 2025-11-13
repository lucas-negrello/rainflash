<?php

namespace App\Enums;

enum CompanyStatusEnum: int
{
    case SUSPENDED = 0;
    case ACTIVE = 1;
    case TRIAL = 2;

    public function label()
    {
        return match ($this) {
            self::SUSPENDED => 'Suspensa',
            self::ACTIVE => 'Ativa',
            self::TRIAL => 'Trial',
        };
    }
}
