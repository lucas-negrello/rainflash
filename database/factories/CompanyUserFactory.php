<?php

namespace Database\Factories;

use App\Models\{Company, CompanyUser, RateHistory, WorkSchedule};
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
            'user_id' => \Database\Factories\UserFactory::new(),
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

    public function withWorkSchedules(int $count = 1): static
    {
        return $this->afterCreating(function (CompanyUser $cu) use ($count) {
            WorkSchedule::factory()->count($count)->create(['company_user_id' => $cu->id]);
        });
    }

    public function withRateHistory(int $count = 1): static
    {
        return $this->afterCreating(function (CompanyUser $cu) use ($count) {
            RateHistory::factory()->count($count)->create(['company_user_id' => $cu->id]);
        });
    }
}
