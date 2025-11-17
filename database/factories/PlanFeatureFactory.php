<?php

namespace Database\Factories;

use App\Models\{PlanFeature, Plan, Feature};
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<PlanFeature> */
class PlanFeatureFactory extends Factory
{
    protected $model = PlanFeature::class;

    public function definition(): array
    {
        return [
            'plan_id' => Plan::factory(),
            'feature_id' => Feature::factory(),
            'value' => ['limit' => fake()->numberBetween(10, 1000)],
            'meta' => ['default' => true],
        ];
    }
}

