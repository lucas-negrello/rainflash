<?php

use App\Enums\CalendarEventTypeEnum;

it('CalendarEventTypeEnum labels', function () {
    $map = [
        CalendarEventTypeEnum::HOLIDAY->value => 'Feriado',
        CalendarEventTypeEnum::COMPANY_EVENT->value => 'Evento da Empresa',
        CalendarEventTypeEnum::BLOCK->value => 'Bloqueio',
    ];

    foreach (CalendarEventTypeEnum::cases() as $case) {
        expect($case->label())->toBe($map[$case->value]);
    }
});

it('has expected numeric values for CalendarEventTypeEnum', function () {
    expect(CalendarEventTypeEnum::HOLIDAY->value)->toBe(0)
        ->and(CalendarEventTypeEnum::COMPANY_EVENT->value)->toBe(1)
        ->and(CalendarEventTypeEnum::BLOCK->value)->toBe(2);
});
