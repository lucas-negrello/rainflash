<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum CompanyStatusEnum: int implements TableEnumInterface
{
    use HasTableEnum;

    case SUSPENDED = 0;
    case ACTIVE = 1;
    case TRIAL = 2;

    public function label(): string
    {
        return match ($this) {
            self::SUSPENDED => 'Suspensa',
            self::ACTIVE => 'Ativa',
            self::TRIAL => 'Trial',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SUSPENDED => 'gray',
            self::ACTIVE => 'green',
            self::TRIAL => 'blue',
        };
    }
}
