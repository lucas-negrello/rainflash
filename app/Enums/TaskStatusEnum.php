<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum TaskStatusEnum: int implements TableEnumInterface
{
    use HasTableEnum;

    case OPEN = 0;
    case IN_PROGRESS = 1;
    case DONE = 2;
    case BLOCKED = 3;

    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Aberta',
            self::IN_PROGRESS => 'Em progresso',
            self::DONE => 'Finalizada',
            self::BLOCKED => 'Bloqueada',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::OPEN => 'gray',
            self::IN_PROGRESS => 'blue',
            self::DONE => 'green',
            self::BLOCKED => 'red',
        };
    }
}
