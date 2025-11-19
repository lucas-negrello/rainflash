<?php

use App\Enums\RoleScopeEnum;

it('has expected values for RoleScopeEnum', function () {
    expect(RoleScopeEnum::GLOBAL->value)->toBe(0)
        ->and(RoleScopeEnum::COMPANY->value)->toBe(1);
});

it('returns correct labels for RoleScopeEnum', function () {
    expect(RoleScopeEnum::GLOBAL->label())->toBe('Global');
    expect(RoleScopeEnum::COMPANY->label())->toBe('Empresa');
});

it('return an array with all objects of the enum', function () {
    $options = RoleScopeEnum::labels();
    $map = [
        RoleScopeEnum::GLOBAL->value => 'Global',
        RoleScopeEnum::COMPANY->value => 'Empresa',
    ];

    foreach ($map as $case => $label) {
        expect($options[$case])->toBe($label);
    }
});

it('provides colors mapping for RoleScopeEnum', function () {
    expect(RoleScopeEnum::colors())->toEqual([
        RoleScopeEnum::GLOBAL->value => 'purple',
        RoleScopeEnum::COMPANY->value => 'blue',
    ]);
});

it('fromValue and options equivalence for RoleScopeEnum', function () {
    expect(RoleScopeEnum::fromValue(1))->toBe(RoleScopeEnum::COMPANY)
        ->and(RoleScopeEnum::dropdownOptions())->toEqual(RoleScopeEnum::labels())
        ->toEqual(RoleScopeEnum::toSelectOptions());
});
