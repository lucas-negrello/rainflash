<?php

namespace Database\Factories;

use App\Enums\UserSkillProficiencyLevelEnum;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserSkill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserSkill>
 */
class UserSkillFactory extends Factory
{
    protected $model = UserSkill::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'skill_id' => Skill::factory(),
            'proficiency_level' => fake()->randomElement(UserSkillProficiencyLevelEnum::cases()),
            'years_of_experience' => fake()->numberBetween(0, 20),
            'last_used_at' => fake()->dateTimeBetween('-2 years', 'now'),
            'meta' => [],
        ];
    }
}

