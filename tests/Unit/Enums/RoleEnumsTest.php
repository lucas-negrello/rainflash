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
