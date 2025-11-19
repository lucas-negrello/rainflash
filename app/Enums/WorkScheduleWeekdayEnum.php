<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum WorkScheduleWeekdayEnum: int implements TableEnumInterface
{
    use HasTableEnum;

    case MONDAY = 1;
    case TUESDAY = 2;
    case WEDNESDAY = 3;
    case THURSDAY = 4;
    case FRIDAY = 5;
    case SATURDAY = 6;
    case SUNDAY = 7;

    public function label(): string
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

    public function shortLabel(): string
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

    public function abbreviation(): string
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

    public function color(): string
    {
        return 'gray';
    }
}
