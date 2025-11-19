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
        WorkScheduleWeekdayEnum::MONDAY->value => ['Segunda-feira', 'Seg', 'S'],
        WorkScheduleWeekdayEnum::TUESDAY->value => ['Terça-feira', 'Ter', 'T'],
        WorkScheduleWeekdayEnum::WEDNESDAY->value => ['Quarta-feira', 'Qua', 'Q'],
        WorkScheduleWeekdayEnum::THURSDAY->value => ['Quinta-feira', 'Qui', 'Q'],
        WorkScheduleWeekdayEnum::FRIDAY->value => ['Sexta-feira', 'Sex', 'S'],
        WorkScheduleWeekdayEnum::SATURDAY->value => ['Sábado', 'Sáb', 'S'],
        WorkScheduleWeekdayEnum::SUNDAY->value => ['Domingo', 'Dom', 'D'],
    ];

    foreach (WorkScheduleWeekdayEnum::cases() as $case) {
        [$label, $short, $abbr] = $map[$case->value];
        expect($case->label())->toBe($label)
            ->and($case->shortLabel())->toBe($short)
            ->and($case->abbreviation())->toBe($abbr);
    }
});

it('provides colors mapping for WorkScheduleWeekdayEnum', function () {
    expect(WorkScheduleWeekdayEnum::colors())->toEqual([
        1 => 'gray', 2 => 'gray', 3 => 'gray', 4 => 'gray', 5 => 'gray', 6 => 'gray', 7 => 'gray'
    ]);
});

it('fromValue and options equivalence for WorkScheduleWeekdayEnum', function () {
    expect(WorkScheduleWeekdayEnum::fromValue(1))->toBe(WorkScheduleWeekdayEnum::MONDAY)
        ->and(WorkScheduleWeekdayEnum::dropdownOptions())->toEqual(WorkScheduleWeekdayEnum::labels())
        ->toEqual(WorkScheduleWeekdayEnum::toSelectOptions());
});
