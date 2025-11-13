<?php

namespace Database\Factories;

use App\Enums\WebhookDeliveryStatusEnum;
use App\Models\{CompanyWebhook, WebhookDelivery};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WebhookDelivery>
 */
class WebhookDeliveryFactory extends Factory
{
    protected $model = WebhookDelivery::class;

    public function definition(): array
    {
        return [
            'webhook_id' => CompanyWebhook::factory(),
            'event_key' => fake()->randomElement(['user.created','company.updated']),
            'payload_snipped' => ['id' => fake()->uuid()],
            'status' => WebhookDeliveryStatusEnum::PENDING,
            'attempt_count' => 0,
            'last_error' => null,
            'delivered_at' => null,
            'meta' => [],
        ];
    }

    public function sent(): static
    {
        return $this->state(fn () => [
            'status' => WebhookDeliveryStatusEnum::SENT,
            'delivered_at' => now(),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn () => [
            'status' => WebhookDeliveryStatusEnum::FAILED,
            'last_error' => 'Something went wrong',
        ]);
    }
}

