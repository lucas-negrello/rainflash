<?php

use App\Enums\CompanyStatusEnum;

it('has expected values for CompanyStatusEnum', function () {
    expect(CompanyStatusEnum::SUSPENDED->value)->toBe(0)
        ->and(CompanyStatusEnum::ACTIVE->value)->toBe(1)
        ->and(CompanyStatusEnum::TRIAL->value)->toBe(2);
});

it('returns correct labels for CompanyStatusEnum', function () {
    $map = [
        CompanyStatusEnum::SUSPENDED->value => 'Suspensa',
        CompanyStatusEnum::ACTIVE->value => 'Ativa',
        CompanyStatusEnum::TRIAL->value => 'Trial',
    ];

    foreach (CompanyStatusEnum::cases() as $case) {
        expect($case->label())->toBe($map[$case->value]);
    }
});

it('return an array with all objects of the enum', function () {
    $options = CompanyStatusEnum::labels();
    $map = [
        CompanyStatusEnum::SUSPENDED->value => 'Suspensa',
        CompanyStatusEnum::ACTIVE->value => 'Ativa',
        CompanyStatusEnum::TRIAL->value => 'Trial',
    ];

    foreach ($map as $case => $label) {
        expect($options[$case])->toBe($label);
    }
});
