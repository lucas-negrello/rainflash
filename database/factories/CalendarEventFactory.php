<?php

namespace Database\Factories;

use App\Enums\CalendarEventTypeEnum;
use App\Models\{Calendar, CalendarEvent};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CalendarEvent>
 */
class CalendarEventFactory extends Factory
{
    protected $model = CalendarEvent::class;

    public function definition(): array
    {
        $date = now()->setTime(0,0)->addDays(fake()->unique()->numberBetween(1, 365));

        return [
            'calendar_id' => Calendar::factory(),
            'date' => $date,
            'type' => fake()->randomElement(CalendarEventTypeEnum::cases()),
            'hours' => fake()->optional()->randomFloat(1, 1, 8),
            'note' => fake()->optional()->sentence(),
            'meta' => [],
        ];
    }
}

