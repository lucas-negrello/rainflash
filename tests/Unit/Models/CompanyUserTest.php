<?php

use App\Models\{Company, CompanyUser, Role, User};

it('relations on CompanyUser (company, user, roles)', function () {
    $company = Company::factory()->create();
    $user = User::factory()->create();
    $cu = CompanyUser::factory()->for($company)->for($user)->create();

    $role = Role::factory()->create();
    $cu->roles()->attach($role->id);

    expect($cu->company->is($company))->toBeTrue()
        ->and($cu->user->is($user))->toBeTrue()
        ->and($cu->roles()->count())->toBe(1);
});

