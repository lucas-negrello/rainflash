<?php

namespace Database\Factories;

use App\Enums\WorkScheduleWeekdayEnum;
use App\Models\WorkSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkSchedule>
 */
class WorkScheduleFactory extends Factory
{
    protected $model = WorkSchedule::class;

    public function definition(): array
    {
        $base = now()->startOfWeek()->setTime(9, 0, 0);
        $from = (clone $base)->addMinutes(fake()->unique()->numberBetween(0, 100000));
        $to = (clone $from)->addMonths(fake()->numberBetween(1, 6));

        return [
            'company_user_id' => null, // set via ->for(CompanyUser::factory()) or explicitly in tests
            'weekday' => fake()->randomElement(WorkScheduleWeekdayEnum::cases()),
            'effective_from' => $from,
            'effective_to' => fake()->boolean(70) ? $to : null,
            'daily_hours' => fake()->randomFloat(1, 4, 10),
            'start_time' => '09:00:00',
            'end_time' => '18:00:00',
            'meta' => [],
        ];
    }
}
