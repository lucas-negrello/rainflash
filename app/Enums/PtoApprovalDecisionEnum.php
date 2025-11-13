<?php

namespace App\Enums;

enum PtoApprovalDecisionEnum: int
{
    case REJECTED = 0;
    case APPROVED = 1;

    public function label(): string
    {
        return match ($this) {
            self::REJECTED => 'Rejeitado',
            self::APPROVED => 'Aprovado',
        };
    }
}
