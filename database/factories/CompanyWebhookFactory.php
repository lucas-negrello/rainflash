<?php

namespace Database\Factories;

use App\Models\{Company, CompanyWebhook};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CompanyWebhook>
 */
class CompanyWebhookFactory extends Factory
{
    protected $model = CompanyWebhook::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'url' => fake()->url(),
            'secret' => fake()->sha256(),
            'active' => true,
            'event_filters' => ['user.created','company.updated'],
            'retry_policy' => ['max_attempts' => 3, 'backoff_seconds' => 30],
            'meta' => [],
        ];
    }
}

