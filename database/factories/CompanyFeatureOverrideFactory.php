<?php

namespace Database\Factories;

use App\Models\{CompanyFeatureOverride, Company, Feature};
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<CompanyFeatureOverride> */
class CompanyFeatureOverrideFactory extends Factory
{
    protected $model = CompanyFeatureOverride::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'feature_id' => Feature::factory(),
            'value' => ['limit' => fake()->numberBetween(1, 100)],
            'meta' => ['reason' => fake()->word()],
        ];
    }
}

