<?php

use App\Models\{Company, CompanyUser, Role, User, WorkSchedule, RateHistory};

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

it('has many WorkSchedules and RateHistory', function () {
    $cu = CompanyUser::factory()->withWorkSchedules(2)->withRateHistory(3)->create();

    expect($cu->workSchedules()->count())->toBe(2)
        ->and($cu->rateHistory()->count())->toBe(3);
});
