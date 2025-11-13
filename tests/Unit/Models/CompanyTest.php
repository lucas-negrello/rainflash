<?php

use App\Enums\CompanyStatusEnum;
use App\Models\{Company, ReportJob, AuditLog, User};

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

it('has many auditLogs and reportJobs', function () {
    $company = Company::factory()->create();
    AuditLog::factory()->count(2)->create(['company_id' => $company->id]);
    ReportJob::factory()->count(3)->create(['company_id' => $company->id]);

    expect($company->auditLogs()->count())->toBe(2)
        ->and($company->reportJobs()->count())->toBe(3);
});
