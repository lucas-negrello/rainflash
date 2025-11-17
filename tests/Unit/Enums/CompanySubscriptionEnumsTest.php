<?php

use App\Enums\CompanySubscriptionStatusEnum;

it('has expected values for CompanySubscriptionStatusEnum', function () {
    expect(CompanySubscriptionStatusEnum::CANCELLED->value)->toBe(0)
        ->and(CompanySubscriptionStatusEnum::ACTIVE->value)->toBe(1)
        ->and(CompanySubscriptionStatusEnum::PAST_DUE->value)->toBe(2)
        ->and(CompanySubscriptionStatusEnum::TRIAL->value)->toBe(3);
});

it('returns correct labels for CompanySubscriptionStatusEnum', function () {
    $map = [
        CompanySubscriptionStatusEnum::CANCELLED->value => 'Cancelado',
        CompanySubscriptionStatusEnum::ACTIVE->value => 'Ativo',
        CompanySubscriptionStatusEnum::PAST_DUE->value => 'Vencido',
        CompanySubscriptionStatusEnum::TRIAL->value => 'Trial',
    ];

    foreach (CompanySubscriptionStatusEnum::cases() as $case) {
        expect($case->label())->toBe($map[$case->value]);
    }
});

