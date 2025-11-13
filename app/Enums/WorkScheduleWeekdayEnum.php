<?php

namespace App\Enums;

enum WorkScheduleWeekdayEnum: int
{
    case MONDAY = 1;
    case TUESDAY = 2;
    case WEDNESDAY = 3;
    case THURSDAY = 4;
    case FRIDAY = 5;
    case SATURDAY = 6;
    case SUNDAY = 7;

    public function label()
    {
        return match ($this) {
            self::MONDAY => 'Segunda-feira',
            self::TUESDAY => 'Terça-feira',
            self::WEDNESDAY => 'Quarta-feira',
            self::THURSDAY => 'Quinta-feira',
            self::FRIDAY => 'Sexta-feira',
            self::SATURDAY => 'Sábado',
            self::SUNDAY => 'Domingo',
        };
    }

    public function shortLabel()
    {
        return match ($this) {
            self::MONDAY => 'Seg',
            self::TUESDAY => 'Ter',
            self::WEDNESDAY => 'Qua',
            self::THURSDAY => 'Qui',
            self::FRIDAY => 'Sex',
            self::SATURDAY => 'Sáb',
            self::SUNDAY => 'Dom',
        };
    }

    public function abbreviation()
    {
        return match ($this) {
            self::MONDAY => 'S',
            self::TUESDAY => 'T',
            self::WEDNESDAY => 'Q',
            self::THURSDAY => 'Q',
            self::FRIDAY => 'S',
            self::SATURDAY => 'S',
            self::SUNDAY => 'D',
        };
    }
}
