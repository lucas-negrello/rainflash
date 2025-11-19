<?php

use App\Enums\CompanyStatusEnum;
use App\Enums\CalendarEventTypeEnum;

it('provides labels via trait', function () {
    expect(CompanyStatusEnum::labels())->toMatchArray([
        CompanyStatusEnum::SUSPENDED->value => 'Suspensa',
        CompanyStatusEnum::ACTIVE->value => 'Ativa',
        CompanyStatusEnum::TRIAL->value => 'Trial',
    ]);
});

it('dropdownOptions matches labels for BC', function () {
    expect(CompanyStatusEnum::dropdownOptions())->toEqual(CompanyStatusEnum::labels());
});

it('provides colors mapping', function () {
    expect(CalendarEventTypeEnum::colors())->toMatchArray([
        CalendarEventTypeEnum::HOLIDAY->value => 'green',
        CalendarEventTypeEnum::COMPANY_EVENT->value => 'blue',
        CalendarEventTypeEnum::BLOCK->value => 'red',
    ]);
});

it('resolves fromValue successfully', function () {
    $case = CompanyStatusEnum::fromValue(1);
    expect($case)->toBe(CompanyStatusEnum::ACTIVE);
});

it('throws on invalid fromValue', function () {
    expect(fn () => CompanyStatusEnum::fromValue(999))->toThrow(InvalidArgumentException::class);
});

it('toSelectOptions returns labels mapping', function () {
    expect(CalendarEventTypeEnum::toSelectOptions())->toEqual(CalendarEventTypeEnum::labels());
});

