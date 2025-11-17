<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Plan> */
class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition(): array
    {
        return [
            'key' => 'plan_'.fake()->unique()->bothify('##??'),
            'name' => fake()->words(2, true),
            'price_monthly' => fake()->randomFloat(2, 0, 999),
            'currency' => 'BRL',
            'meta' => ['tier' => fake()->randomElement(['starter','pro','enterprise'])],
        ];
    }
}

