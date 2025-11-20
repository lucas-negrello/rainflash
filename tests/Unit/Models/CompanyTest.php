<?php

use App\Enums\CompanyStatusEnum;
use App\Enums\CompanySubscriptionStatusEnum;
use App\Enums\FeatureTypeEnum;
use App\Models\{Company, ReportJob, AuditLog, User, Team, PtoRequest, CompanyWebhook, Calendar, Plan, Feature, CompanyFeatureOverride, Project, Task, TimeEntry, Assignment, CompanyUser};
use Database\Factories\{CompanyFactory, PlanFactory, FeatureFactory, CompanyFeatureOverrideFactory, ProjectFactory, TaskFactory, TimeEntryFactory, AssignmentFactory, CompanyUserFactory};

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

it('has many projects', function () {
    $company = Company::factory()->create();
    Project::factory()->count(2)->create(['company_id' => $company->id]);

    expect($company->projects()->count())->toBe(2);
});

it('has feature overrides with pivot data', function () {
    $plan = PlanFactory::new()->create();
    $company = CompanyFactory::new()->create([
        'status' => CompanyStatusEnum::ACTIVE,
        'current_plan_id' => $plan->id,
        'subscription_status' => CompanySubscriptionStatusEnum::ACTIVE,
        'subscription_period_start' => now(),
        'subscription_period_end' => now()->addMonth(),
    ]);

    $feature = FeatureFactory::new()->create(['type' => FeatureTypeEnum::LIMIT]);
    CompanyFeatureOverrideFactory::new()->create(['company_id' => $company->id, 'feature_id' => $feature->id, 'value' => ['limit' => 5], 'meta' => ['reason' => 'ok']]);

    $override = $company->companyFeatureOverrides()->first();
    $decodedValue = is_string($override->value) ? json_decode($override->value, true) : $override->value;
    $decodedMeta = is_string($override->meta) ? json_decode($override->meta, true) : $override->meta;

    expect($company->companyFeatureOverrides()->count())->toBe(1)
        ->and($decodedValue)->toBe(['limit' => 5])
        ->and($decodedMeta)->toBe(['reason' => 'ok'])
        ->and($company->currentPlan->id)->toBe($plan->id);
});

it('has currentPlan relationship', function () {
    $company = CompanyFactory::new()->create();
    $plan = PlanFactory::new()->create();

    // Set current plan
    $company->update([
        'current_plan_id' => $plan->id,
        'subscription_status' => CompanySubscriptionStatusEnum::ACTIVE,
        'subscription_period_start' => now(),
        'subscription_period_end' => now()->addMonth(),
    ]);

    expect($company->currentPlan)->not->toBeNull()
        ->and($company->currentPlan->id)->toBe($plan->id);
});

it('returns null currentPlan when no plan set', function () {
    $company = CompanyFactory::new()->create();
    expect($company->currentPlan)->toBeNull();
});

it('relations smoke test invokes all relation methods', function () {
    $company = CompanyFactory::new()->create();
    // create related data minimally
    AuditLog::factory()->create(['company_id' => $company->id]);
    ReportJob::factory()->create(['company_id' => $company->id]);
    Team::factory()->create(['company_id' => $company->id]);
    PtoRequest::factory()->create(['company_id' => $company->id]);
    CompanyWebhook::factory()->create(['company_id' => $company->id]);
    Calendar::factory()->create(['company_id' => $company->id]);
    $plan = PlanFactory::new()->create();
    $company->update([
        'current_plan_id' => $plan->id,
        'subscription_status' => \App\Enums\CompanySubscriptionStatusEnum::ACTIVE,
        'subscription_period_start' => now()->subDay(),
        'subscription_period_end' => now()->addDay(),
    ]);
    $feature = FeatureFactory::new()->create();
    CompanyFeatureOverrideFactory::new()->create(['company_id' => $company->id, 'feature_id' => $feature->id]);
    $projectA = ProjectFactory::new()->create(['company_id' => $company->id]);

    // touch each relation
    expect($company->users())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class)
        ->and($company->auditLogs()->count())->toBe(1)
        ->and($company->reportJobs()->count())->toBe(1)
        ->and($company->teams()->count())->toBe(1)
        ->and($company->ptoRequests()->count())->toBe(1)
        ->and($company->webhooks()->count())->toBe(1)
        ->and($company->calendars()->count())->toBe(1)
        ->and($company->companyFeatureOverrides()->count())->toBe(1)
        ->and($company->currentPlan)->toBeInstanceOf(Plan::class)
        ->and($company->projects()->count())->toBe(1);
});

it('has tasks, timeEntries and assignments via projects', function () {
    $company = CompanyFactory::new()->create();
    $project = ProjectFactory::new()->create(['company_id' => $company->id]);
    $creator = CompanyUserFactory::new()->create(['company_id' => $company->id]);

    TaskFactory::new()->create(['project_id' => $project->id, 'created_by_company_user_id' => $creator->id]);
    TimeEntryFactory::new()->create(['project_id' => $project->id, 'created_by_company_user_id' => $creator->id]);
    AssignmentFactory::new()->create(['project_id' => $project->id, 'company_user_id' => $creator->id]);

    expect($company->tasks()->count())->toBe(1)
        ->and($company->timeEntries()->count())->toBe(1)
        ->and($company->assignments()->count())->toBe(1);
});
