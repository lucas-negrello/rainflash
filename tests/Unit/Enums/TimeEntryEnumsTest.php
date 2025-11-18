<?php

use App\Enums\{TimeEntryStatusEnum, TimeEntryOriginEnum};

it('has expected values for TimeEntryStatusEnum', function () {
    expect(TimeEntryStatusEnum::PENDING->value)->toBe(0)
        ->and(TimeEntryStatusEnum::APPROVED->value)->toBe(1)
        ->and(TimeEntryStatusEnum::REPROVED->value)->toBe(2);
});

it('returns correct labels for TimeEntryStatusEnum', function () {
    $map = [
        TimeEntryStatusEnum::PENDING->value => 'Pendente',
        TimeEntryStatusEnum::APPROVED->value => 'Aprovado',
        TimeEntryStatusEnum::REPROVED->value => 'Reprovado',
    ];
    foreach (TimeEntryStatusEnum::cases() as $case) {
        expect($case->label())->toBe($map[$case->value]);
    }
});

it('has expected values for TimeEntryOriginEnum', function () {
    expect(TimeEntryOriginEnum::MANUAL->value)->toBe(0)
        ->and(TimeEntryOriginEnum::COUNTER->value)->toBe(1)
        ->and(TimeEntryOriginEnum::EXTERNAL->value)->toBe(2);
});

it('returns correct labels for TimeEntryOriginEnum', function () {
    $map = [
        TimeEntryOriginEnum::MANUAL->value => 'Manual',
        TimeEntryOriginEnum::COUNTER->value => 'Timer',
        TimeEntryOriginEnum::EXTERNAL->value => 'Externo',
    ];
    foreach (TimeEntryOriginEnum::cases() as $case) {
        expect($case->label())->toBe($map[$case->value]);
    }
});

