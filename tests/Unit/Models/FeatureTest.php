<?php

use App\Models\{Feature, Plan, Company, PlanFeature, CompanyFeatureOverride};
use Database\Factories\{FeatureFactory, PlanFactory, PlanFeatureFactory, CompanyFactory, CompanyFeatureOverrideFactory};

it('creates Feature with casts and relations', function () {
    $feature = FeatureFactory::new()->create();

    $plan = PlanFactory::new()->create();
    PlanFeatureFactory::new()->create(['plan_id' => $plan->id, 'feature_id' => $feature->id]);

    $company = CompanyFactory::new()->create();
    CompanyFeatureOverrideFactory::new()->create(['company_id' => $company->id, 'feature_id' => $feature->id]);

    expect($feature->planFeatures()->count())->toBe(1)
        ->and($feature->companyFeatureOverrides()->count())->toBe(1)
        ->and($feature->meta)->toBeArray()
        ->and($feature->type)->toBeInstanceOf(\App\Enums\FeatureTypeEnum::class);
});
