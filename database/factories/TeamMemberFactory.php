<?php

namespace Database\Factories;

use App\Models\{CompanyUser, Team, TeamMember};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeamMember>
 */
class TeamMemberFactory extends Factory
{
    protected $model = TeamMember::class;

    public function definition(): array
    {
        return [
            'company_user_id' => CompanyUser::factory(),
            'team_id' => Team::factory(),
            'role_in_team' => fake()->randomElement(['Member','Lead','Manager']),
            'joined_at' => fake()->optional()->dateTimeBetween('-1 years', 'now'),
            'left_at' => null,
            'meta' => [],
        ];
    }
}

