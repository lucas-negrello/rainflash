<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum PtoApprovalDecisionEnum: int implements TableEnumInterface
{
    use HasTableEnum;

    case REJECTED = 0;
    case APPROVED = 1;

    public function label(): string
    {
        return match ($this) {
            self::REJECTED => 'Rejeitado',
            self::APPROVED => 'Aprovado',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::REJECTED => 'red',
            self::APPROVED => 'green',
        };
    }
}
