<?php

use App\Enums\WorkScheduleWeekdayEnum;

it('has expected values for WorkScheduleWeekdayEnum', function () {
    expect(WorkScheduleWeekdayEnum::MONDAY->value)->toBe(1)
        ->and(WorkScheduleWeekdayEnum::TUESDAY->value)->toBe(2)
        ->and(WorkScheduleWeekdayEnum::WEDNESDAY->value)->toBe(3)
        ->and(WorkScheduleWeekdayEnum::THURSDAY->value)->toBe(4)
        ->and(WorkScheduleWeekdayEnum::FRIDAY->value)->toBe(5)
        ->and(WorkScheduleWeekdayEnum::SATURDAY->value)->toBe(6)
        ->and(WorkScheduleWeekdayEnum::SUNDAY->value)->toBe(7);
});

it('returns correct labels and abbreviations for all days', function () {
    $map = [
        1 => ['Segunda-feira', 'Seg', 'S'],
        2 => ['Terça-feira', 'Ter', 'T'],
        3 => ['Quarta-feira', 'Qua', 'Q'],
        4 => ['Quinta-feira', 'Qui', 'Q'],
        5 => ['Sexta-feira', 'Sex', 'S'],
        6 => ['Sábado', 'Sáb', 'S'],
        7 => ['Domingo', 'Dom', 'D'],
    ];

    foreach (WorkScheduleWeekdayEnum::cases() as $case) {
        [$label, $short, $abbr] = $map[$case->value];
        expect($case->label())->toBe($label)
            ->and($case->shortLabel())->toBe($short)
            ->and($case->abbreviation())->toBe($abbr);
    }
});
