<?php

use App\Models\{Company, CompanyUser, Role, User, WorkSchedule, RateHistory, AuditLog, ReportJob};

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

it('casts and fillables on CompanyUser', function () {
    $joined = now()->subMonth();
    $left = now();

    $cu = CompanyUser::factory()->create([
        'active' => false,
        'joined_at' => $joined,
        'left_at' => $left,
        'meta' => ['k' => 'v'],
    ]);

    expect($cu->getFillable())
        ->toContain('company_id','user_id','primary_title','currency','active','joined_at','left_at','meta')
        ->and($cu->active)->toBeFalse()
        ->and($cu->joined_at->toDateTimeString())->toBe($joined->toDateTimeString())
        ->and($cu->left_at->toDateTimeString())->toBe($left->toDateTimeString())
        ->and($cu->meta)->toBeArray();
});

it('has auditLogs and reportJobs relations', function () {
    $company = Company::factory()->create();
    $user = User::factory()->create();
    $cu = CompanyUser::factory()->for($company)->for($user)->create();

    AuditLog::factory()->count(2)->create([
        'company_id' => $company->id,
        'actor_user_id' => $user->id,
        'actor_company_user_id' => $cu->id,
    ]);
    ReportJob::factory()->count(3)->create([
        'company_id' => $company->id,
        'requested_by_company_user_id' => $cu->id,
    ]);

    expect($cu->auditLogs()->count())->toBe(2)
        ->and($cu->reportJobs()->count())->toBe(3);
});
