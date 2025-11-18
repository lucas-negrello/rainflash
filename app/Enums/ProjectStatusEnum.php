<?php

namespace App\Enums;

enum ProjectStatusEnum: int
{
    case INACTIVE = 0;
    case ACTIVE = 1;
    case PENDING = 2;
    case COMPLETED = 3;

    public function label()
    {
        return match ($this) {
            ProjectStatusEnum::INACTIVE => 'Inativo',
            ProjectStatusEnum::ACTIVE => 'Ativo',
            ProjectStatusEnum::PENDING => 'Pendente',
            ProjectStatusEnum::COMPLETED => 'Completo',
        };
    }
}
