<?php

use App\Models\{CompanyFeatureOverride, Company, Feature};
use Database\Factories\{CompanyFeatureOverrideFactory};

it('creates CompanyFeatureOverride with casts and relations', function () {
    $override = CompanyFeatureOverrideFactory::new()->create();

    expect($override->company)->toBeInstanceOf(Company::class)
        ->and($override->feature)->toBeInstanceOf(Feature::class)
        ->and($override->value)->toBeArray()
        ->and($override->meta)->toBeArray();
});
