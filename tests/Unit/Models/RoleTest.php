<?php

use App\Enums\RoleScopeEnum;
use App\Models\{Company, CompanyUser, Role, User};

it('casts and fillables on Role', function () {
    $role = Role::factory()->make([
        'scope' => RoleScopeEnum::COMPANY,
        'meta' => ['x' => 'y'],
    ]);

    expect($role->scope)->toBe(RoleScopeEnum::COMPANY)
        ->and($role->getFillable())
        ->toContain('key', 'scope', 'name', 'description', 'meta');
});

it('relates companyUsers via pivot', function () {
    $company = Company::factory()->create();
    $user = User::factory()->create();
    $companyUser = CompanyUser::factory()->for($company)->for($user)->create();

    $role = Role::factory()->create();
    $companyUser->roles()->attach($role->id);

    expect($role->companyUsers()->count())->toBe(1)
        ->and($role->companyUsers()->first()->is($companyUser))->toBeTrue();
});

