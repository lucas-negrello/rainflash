<?php

use App\Enums\CalendarScopeEnum;

it('CalendarScopeEnum labels', function () {
    $map = [
        CalendarScopeEnum::COMPANY->value => 'Empresarial',
        CalendarScopeEnum::REGIONAL->value => 'Regional',
        CalendarScopeEnum::USER->value => 'Particular',
    ];

    foreach (CalendarScopeEnum::cases() as $case) {
        expect($case->label())->toBe($map[$case->value]);
    }
});

it('has expected numeric values for CalendarScopeEnum', function () {
    expect(CalendarScopeEnum::COMPANY->value)->toBe(0)
        ->and(CalendarScopeEnum::REGIONAL->value)->toBe(1)
        ->and(CalendarScopeEnum::USER->value)->toBe(2);
});
