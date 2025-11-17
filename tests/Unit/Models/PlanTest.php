<?php

use App\Models\{Plan, Feature, Company, CompanySubscription, PlanFeature};
use Database\Factories\{PlanFactory, FeatureFactory, CompanyFactory, CompanySubscriptionFactory, PlanFeatureFactory};

it('creates Plan with casts and relations', function () {
    $plan = PlanFactory::new()->create();

    $feature = FeatureFactory::new()->create();
    PlanFeatureFactory::new()->create(['plan_id' => $plan->id, 'feature_id' => $feature->id]);

    CompanySubscriptionFactory::new()->create(['plan_id' => $plan->id]);

    expect($plan->features()->count())->toBe(1)
        ->and($plan->planFeatures()->count())->toBe(1)
        ->and($plan->companies()->count())->toBe(1)
        ->and($plan->price_monthly)->toBeFloat()
        ->and($plan->meta)->toBeArray();
});

it('relates companies via subscriptions pivot and lists companySubscriptions', function () {
    $plan = PlanFactory::new()->create();
    $company = CompanyFactory::new()->create();
    CompanySubscriptionFactory::new()->create(['plan_id' => $plan->id, 'company_id' => $company->id]);

    expect($plan->companies()->count())->toBe(1)
        ->and($plan->companySubscriptions()->count())->toBe(1);
});
