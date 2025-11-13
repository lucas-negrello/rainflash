<?php

use App\Enums\CompanyStatusEnum;
use App\Models\{Company, ReportJob, AuditLog, User, Team, PtoRequest, CompanyWebhook, Calendar};

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

it('has many teams', function () {
    $company = Company::factory()->create();
    Team::factory()->count(2)->create(['company_id' => $company->id]);

    expect($company->teams()->count())->toBe(2);
});

it('has many ptoRequests', function () {
    $company = Company::factory()->create();
    PtoRequest::factory()->count(2)->create(['company_id' => $company->id]);

    expect($company->ptoRequests()->count())->toBe(2);
});

it('has many webhooks', function () {
    $company = Company::factory()->create();
    CompanyWebhook::factory()->count(2)->create(['company_id' => $company->id]);

    expect($company->webhooks()->count())->toBe(2);
});

it('has many calendars', function () {
    $company = Company::factory()->create();
    Calendar::factory()->count(2)->create(['company_id' => $company->id]);

    expect($company->calendars()->count())->toBe(2);
});
