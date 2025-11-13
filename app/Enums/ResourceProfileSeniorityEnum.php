<?php

namespace App\Enums;

enum ResourceProfileSeniorityEnum: int
{
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
}
