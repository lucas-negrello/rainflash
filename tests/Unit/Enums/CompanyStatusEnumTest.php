<?php

use App\Enums\CompanyStatusEnum;

it('has expected values for CompanyStatusEnum', function () {
    expect(CompanyStatusEnum::SUSPENDED->value)->toBe(0)
        ->and(CompanyStatusEnum::ACTIVE->value)->toBe(1)
        ->and(CompanyStatusEnum::TRIAL->value)->toBe(2);
});

it('returns correct labels for CompanyStatusEnum', function () {
    expect(CompanyStatusEnum::SUSPENDED->label())->toBe('Suspensa');
    expect(CompanyStatusEnum::ACTIVE->label())->toBe('Ativa');
    expect(CompanyStatusEnum::TRIAL->label())->toBe('Trial');
});
