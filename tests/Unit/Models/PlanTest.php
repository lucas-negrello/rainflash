<?php

use App\Models\{Plan, Feature, Company, PlanFeature};
use Database\Factories\{PlanFactory, FeatureFactory, CompanyFactory, PlanFeatureFactory};

it('creates Plan with casts and relations', function () {
    $plan = PlanFactory::new()->create();

    $feature = FeatureFactory::new()->create();
    PlanFeatureFactory::new()->create(['plan_id' => $plan->id, 'feature_id' => $feature->id]);

    // Create a company using this plan
    CompanyFactory::new()->create(['current_plan_id' => $plan->id]);

    expect($plan->features()->count())->toBe(1)
        ->and($plan->planFeatures()->count())->toBe(1)
        ->and($plan->companies()->count())->toBe(1)
        ->and($plan->price_monthly)->toBeFloat()
        ->and($plan->meta)->toBeArray();
});

it('relates companies directly via current_plan_id', function () {
    $plan = PlanFactory::new()->create();
    $company = CompanyFactory::new()->create([
        'current_plan_id' => $plan->id,
        'subscription_status' => \App\Enums\CompanySubscriptionStatusEnum::ACTIVE,
        'subscription_period_start' => now(),
        'subscription_period_end' => now()->addMonth(),
    ]);

    expect($plan->companies()->count())->toBe(1)
        ->and($plan->companies()->first()->id)->toBe($company->id);
});
