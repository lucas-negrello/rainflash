<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum ResourceProfileSeniorityEnum: int implements TableEnumInterface
{
    use HasTableEnum;

    case INTERN = 1;
    case JUNIOR = 2;
    case MID_LEVEL = 3;
    case SENIOR = 4;
    case LEAD = 5;
    case MANAGER = 6;
    case DIRECTOR = 7;
    case EXECUTIVE = 8;

    public function label(): string
    {
        return match ($this) {
            self::INTERN => 'Estagiário',
            self::JUNIOR => 'Júnior',
            self::MID_LEVEL => 'Pleno',
            self::SENIOR => 'Senior',
            self::LEAD => 'Líder',
            self::MANAGER => 'Gerente',
            self::DIRECTOR => 'Diretor',
            self::EXECUTIVE => 'Executivo',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::INTERN => 'gray',
            self::JUNIOR => 'teal',
            self::MID_LEVEL => 'blue',
            self::SENIOR => 'purple',
            self::LEAD => 'orange',
            self::MANAGER => 'yellow',
            self::DIRECTOR => 'red',
            self::EXECUTIVE => 'green',
        };
    }
}
