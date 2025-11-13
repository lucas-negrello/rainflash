<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CompanyUser>
 */
class CompanyUserFactory extends Factory
{
    protected $model = CompanyUser::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'user_id' => User::factory(),
            'primary_title' => fake()->optional()->jobTitle(),
            'currency' => fake()->optional()->currencyCode(),
            'active' => true,
            'joined_at' => fake()->optional()->dateTimeBetween('-2 years', 'now'),
            'left_at' => null,
            'meta' => [],
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => [
            'active' => false,
            'left_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }
}

