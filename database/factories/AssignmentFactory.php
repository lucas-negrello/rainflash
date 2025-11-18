<?php

namespace Database\Factories;

use App\Models\{Assignment, Project, CompanyUser};
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Assignment> */
class AssignmentFactory extends Factory
{
    protected $model = Assignment::class;

    public function definition(): array
    {
        $from = now()->subDays(fake()->numberBetween(1, 30));
        return [
            'project_id' => Project::factory(),
            'company_user_id' => CompanyUser::factory(),
            'effective_from' => $from,
            'effective_to' => fake()->optional(0.5)->boolean() ? $from->copy()->addDays(fake()->numberBetween(1, 60)) : null,
            'weekly_capacity_hours' => fake()->randomFloat(1, 5, 40),
            'hour_rate_override' => fake()->optional()->randomFloat(2, 10, 200),
            'price_rate_override' => fake()->optional()->randomFloat(2, 10, 300),
            'meta' => ['note' => fake()->word()],
        ];
    }
}

