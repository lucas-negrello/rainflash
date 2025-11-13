<?php

namespace Database\Factories;

use App\Enums\{ReportJobStatusEnum, ReportJobTypeEnum};
use App\Models\{Company, CompanyUser, ReportJob};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReportJob>
 */
class ReportJobFactory extends Factory
{
    protected $model = ReportJob::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'requested_by_company_user_id' => CompanyUser::factory(),
            'type' => fake()->randomElement(ReportJobTypeEnum::cases()),
            'status' => ReportJobStatusEnum::PENDING,
            'parameters' => ['date_from' => now()->toDateString(), 'date_to' => now()->toDateString()],
            'storage_key' => null,
            'meta' => [],
        ];
    }

    public function processing(): static
    {
        return $this->state(fn () => ['status' => ReportJobStatusEnum::PROCESSING]);
    }

    public function done(): static
    {
        return $this->state(fn () => ['status' => ReportJobStatusEnum::DONE]);
    }

    public function failed(): static
    {
        return $this->state(fn () => ['status' => ReportJobStatusEnum::FAILED]);
    }
}

