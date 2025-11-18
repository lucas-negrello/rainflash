<?php

namespace App\Enums;

enum TaskStatusEnum: int
{
    case OPEN = 0;
    case IN_PROGRESS = 1;
    case DONE = 2;
    case BLOCKED = 3;

    public function label()
    {
        return match ($this) {
            TaskStatusEnum::OPEN => 'Aberta',
            TaskStatusEnum::IN_PROGRESS => 'Em progresso',
            TaskStatusEnum::DONE => 'Finalizada',
            TaskStatusEnum::BLOCKED => 'Bloqueada',
        };
    }
}
