<?php

namespace Database\Factories;

use App\Models\{Company, Team};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition(): array
    {
        $name = Str::title(fake()->unique()->words(2, true));
        return [
            'company_id' => Company::factory(),
            'name' => $name,
            'description' => fake()->optional()->sentence(),
            'meta' => [],
        ];
    }
}

