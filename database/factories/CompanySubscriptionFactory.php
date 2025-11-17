<?php

namespace Database\Factories;

use App\Enums\CompanySubscriptionStatusEnum;
use App\Models\{CompanySubscription, Company, Plan};
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<CompanySubscription> */
class CompanySubscriptionFactory extends Factory
{
    protected $model = CompanySubscription::class;

    public function definition(): array
    {
        $start = now()->startOfMonth();
        return [
            'company_id' => Company::factory(),
            'plan_id' => Plan::factory(),
            'status' => CompanySubscriptionStatusEnum::ACTIVE,
            'seats_limit' => fake()->numberBetween(5, 100),
            'period_start' => $start,
            'period_end' => $start->copy()->addMonth()->subSecond(),
            'trial_end' => null,
            'meta' => ['note' => fake()->sentence()],
        ];
    }

    public function trial(): static
    {
        return $this->state(function () {
            $start = now();
            return [
                'status' => CompanySubscriptionStatusEnum::TRIAL,
                'trial_end' => $start->copy()->addDays(14),
            ];
        });
    }
}

