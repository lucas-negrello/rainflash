<?php

use App\Models\{PlanFeature, Plan, Feature};
use Database\Factories\{PlanFeatureFactory};

it('creates PlanFeature with casts and relations', function () {
    $pf = PlanFeatureFactory::new()->create();

    expect($pf->plan)->toBeInstanceOf(Plan::class)
        ->and($pf->feature)->toBeInstanceOf(Feature::class)
        ->and($pf->value)->toBeArray()
        ->and($pf->meta)->toBeArray();
});
