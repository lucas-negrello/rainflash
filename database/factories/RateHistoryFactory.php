<?php

namespace Database\Factories;

use App\Models\RateHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RateHistory>
 */
class RateHistoryFactory extends Factory
{
    protected $model = RateHistory::class;

    public function definition(): array
    {
        $base = now()->subMonths(6)->startOfDay();
        $from = (clone $base)->addMinutes(fake()->unique()->numberBetween(0, 100000));
        $to = (clone $from)->addMonths(fake()->numberBetween(1, 6));

        return [
            'company_user_id' => null, // set via ->for(CompanyUser::factory()) or explicitly
            'effective_from' => $from,
            'effective_to' => fake()->boolean(70) ? $to : null,
            'hour_cost' => fake()->randomFloat(2, 20, 300),
            'currency' => 'USD',
            'meta' => [],
        ];
    }
}
