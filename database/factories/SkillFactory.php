<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Skill>
 */
class SkillFactory extends Factory
{
    protected $model = Skill::class;

    public function definition(): array
    {
        $name = Str::title(fake()->unique()->words(2, true));
        return [
            'key' => Str::slug($name.'-'.fake()->unique()->bothify('###')),
            'name' => $name,
            'category' => Str::title(fake()->randomElement(['Backend', 'Frontend', 'DevOps', 'Data', 'QA', 'Mobile'])),
            'meta' => [],
        ];
    }
}

