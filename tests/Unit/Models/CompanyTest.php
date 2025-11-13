<?php

use App\Enums\CompanyStatusEnum;
use App\Models\{Company, User};

it('casts and fillables on Company', function () {
    $company = Company::factory()->make([
        'status' => CompanyStatusEnum::TRIAL,
        'meta' => ['a' => 1],
    ]);

    expect($company->status)->toBe(CompanyStatusEnum::TRIAL)
        ->and($company->getFillable())
        ->toContain('name', 'slug', 'status', 'meta');
});

it('relates users via company_user pivot', function () {
    $company = Company::factory()->create();
    $users = User::factory()->count(2)->create();

    $company->users()->attach($users->pluck('id'), [
        'active' => true,
        'currency' => 'USD',
    ]);

    expect($company->users()->count())->toBe(2)
        ->and((bool) $company->users()->first()->pivot->active)->toBeTrue();
});
