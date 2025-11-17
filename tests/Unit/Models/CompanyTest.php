<?php

use App\Enums\CompanyStatusEnum;
use App\Models\{Company, ReportJob, AuditLog, User, Team, PtoRequest, CompanyWebhook, Calendar, Plan, CompanySubscription, Feature, CompanyFeatureOverride};
use Database\Factories\{CompanyFactory, PlanFactory, CompanySubscriptionFactory, FeatureFactory, CompanyFeatureOverrideFactory};

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

it('has subscriptions and feature overrides and features pivot data', function () {
    $plan = PlanFactory::new()->create();
    $company = CompanyFactory::new()->create(['status' => CompanyStatusEnum::ACTIVE]);
    CompanySubscriptionFactory::new()->create(['company_id' => $company->id, 'plan_id' => $plan->id]);

    $feature = FeatureFactory::new()->create();
    CompanyFeatureOverrideFactory::new()->create(['company_id' => $company->id, 'feature_id' => $feature->id, 'value' => ['limit' => 5], 'meta' => ['reason' => 'ok']]);

    $featureRelation = $company->features()->first();
    $pivotValue = $featureRelation->pivot->value;
    $pivotMeta = $featureRelation->pivot->meta;
    $decodedValue = is_string($pivotValue) ? json_decode($pivotValue, true) : $pivotValue;
    $decodedMeta = is_string($pivotMeta) ? json_decode($pivotMeta, true) : $pivotMeta;

    expect($company->companySubscriptions()->count())->toBe(1)
        ->and($company->companyFeatureOverrides()->count())->toBe(1)
        ->and($company->features()->count())->toBe(1)
        ->and($decodedValue)->toBe(['limit' => 5])
        ->and($decodedMeta)->toBe(['reason' => 'ok']);
});

it('relates plans via subscriptions and resolves currentPlan', function () {
    $company = CompanyFactory::new()->create();
    $planOld = PlanFactory::new()->create();
    $planNew = PlanFactory::new()->create();

    CompanySubscriptionFactory::new()->create(['company_id' => $company->id, 'plan_id' => $planOld->id, 'period_start' => now()->subMonths(2), 'period_end' => now()->subMonth()]);
    CompanySubscriptionFactory::new()->create(['company_id' => $company->id, 'plan_id' => $planNew->id, 'period_start' => now()->subWeek(), 'period_end' => now()->addWeek()]);

    $current = $company->currentPlan();

    expect($company->plans()->count())->toBe(2)
        ->and($current->id)->toBe($planNew->id);
});

it('returns null currentPlan when no subscriptions', function () {
    $company = CompanyFactory::new()->create();
    expect($company->currentPlan())->toBeNull();
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
    CompanySubscriptionFactory::new()->create(['company_id' => $company->id, 'plan_id' => $plan->id, 'period_start' => now()->subDay(), 'period_end' => now()->addDay()]);
    $feature = FeatureFactory::new()->create();
    CompanyFeatureOverrideFactory::new()->create(['company_id' => $company->id, 'feature_id' => $feature->id]);

    // touch each relation
    expect($company->users())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class)
        ->and($company->auditLogs()->count())->toBe(1)
        ->and($company->reportJobs()->count())->toBe(1)
        ->and($company->teams()->count())->toBe(1)
        ->and($company->ptoRequests()->count())->toBe(1)
        ->and($company->webhooks()->count())->toBe(1)
        ->and($company->calendars()->count())->toBe(1)
        ->and($company->companySubscriptions()->count())->toBe(1)
        ->and($company->companyFeatureOverrides()->count())->toBe(1)
        ->and($company->features()->count())->toBe(1)
        ->and($company->plans()->count())->toBe(1)
        ->and($company->currentPlan())->toBeInstanceOf(Plan::class);
});
