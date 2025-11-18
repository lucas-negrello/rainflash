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
