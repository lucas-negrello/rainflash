<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;

enum CompanyStatusEnum: int implements TableEnumInterface
{
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

    public static function labels(): array
    {
        return [
            self::SUSPENDED->value => self::SUSPENDED->label(),
            self::ACTIVE->value => self::ACTIVE->label(),
            self::TRIAL->value => self::TRIAL->label(),
        ];
    }
}
