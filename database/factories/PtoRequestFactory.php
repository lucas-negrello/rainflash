<?php

namespace Database\Factories;

use App\Enums\{PtoRequestHoursOptionEnum, PtoRequestStatusEnum, PtoRequestTypeEnum};
use App\Models\{Company, CompanyUser, PtoRequest};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PtoRequest>
 */
class PtoRequestFactory extends Factory
{
    protected $model = PtoRequest::class;

    public function definition(): array
    {
        $start = now()->addDays(7)->startOfDay();
        $end = (clone $start)->addDays(5)->endOfDay();

        return [
            'company_id' => Company::factory(),
            'company_user_id' => CompanyUser::factory(),
            'approved_by_company_user_id' => null,
            'status' => PtoRequestStatusEnum::REQUESTED,
            'type' => PtoRequestTypeEnum::VACATION,
            'hours_option' => PtoRequestHoursOptionEnum::FULL_DAY,
            'start_date' => $start,
            'end_date' => $end,
            'hours_amount' => null,
            'requested_at' => now(),
            'approved_at' => null,
            'reason' => fake()->optional()->sentence(),
            'meta' => [],
        ];
    }

    public function approved(): static
    {
        return $this->state(fn () => [
            'status' => PtoRequestStatusEnum::APPROVED,
            'approved_at' => now(),
        ]);
    }

    public function withHours(float $hours): static
    {
        return $this->state(fn () => [
            'hours_option' => PtoRequestHoursOptionEnum::CUSTOM_HOURS,
            'hours_amount' => $hours,
        ]);
    }
}

