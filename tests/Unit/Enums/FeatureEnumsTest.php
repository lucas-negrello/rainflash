<?php

use App\Enums\FeatureTypeEnum;

it('has expected values for FeatureTypeEnum', function () {
    expect(FeatureTypeEnum::BOOLEAN->value)->toBe(0)
        ->and(FeatureTypeEnum::LIMIT->value)->toBe(1)
        ->and(FeatureTypeEnum::TIER->value)->toBe(2);
});

it('returns correct labels for FeatureTypeEnum', function () {
    $map = [
        FeatureTypeEnum::BOOLEAN->value => 'Boleano',
        FeatureTypeEnum::LIMIT->value => 'Limite',
        FeatureTypeEnum::TIER->value => 'Nível',
    ];

    foreach (FeatureTypeEnum::cases() as $case) {
        expect($case->label())->toBe($map[$case->value]);
    }
});

it('provides colors mapping for FeatureTypeEnum', function () {
    expect(FeatureTypeEnum::colors())->toEqual([
        FeatureTypeEnum::BOOLEAN->value => 'blue',
        FeatureTypeEnum::LIMIT->value => 'orange',
        FeatureTypeEnum::TIER->value => 'purple',
    ]);
});

it('fromValue and options equivalence for FeatureTypeEnum', function () {
    expect(FeatureTypeEnum::fromValue(1))->toBe(FeatureTypeEnum::LIMIT)
        ->and(FeatureTypeEnum::dropdownOptions())->toEqual(FeatureTypeEnum::labels())
        ->toEqual(FeatureTypeEnum::toSelectOptions());
});
