<?php

namespace Database\Factories;

use App\Enums\FeatureTypeEnum;
use App\Models\Feature;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Feature> */
class FeatureFactory extends Factory
{
    protected $model = Feature::class;

    public function definition(): array
    {
        return [
            'key' => 'feat_'.fake()->unique()->bothify('##??'),
            'name' => fake()->word(),
            'type' => fake()->randomElement(FeatureTypeEnum::cases()),
            'meta' => ['doc' => fake()->url()],
        ];
    }
}

