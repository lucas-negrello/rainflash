<?php

namespace Database\Factories;

use App\Enums\CalendarScopeEnum;
use App\Models\{Calendar, Company};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Calendar>
 */
class CalendarFactory extends Factory
{
    protected $model = Calendar::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => Str::title(fake()->unique()->words(2, true)),
            'scope' => fake()->randomElement(CalendarScopeEnum::cases()),
            'region_code' => fake()->optional()->countryCode(),
            'meta' => [],
        ];
    }
}

