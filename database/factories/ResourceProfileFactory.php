<?php

namespace Database\Factories;

use App\Enums\ResourceProfileSeniorityEnum;
use App\Models\ResourceProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResourceProfile>
 */
class ResourceProfileFactory extends Factory
{
    protected $model = ResourceProfile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'seniority' => fake()->randomElement(ResourceProfileSeniorityEnum::cases()),
            'headline' => fake()->optional()->sentence(6),
            'bio' => fake()->optional()->paragraph(),
            'location' => fake()->optional()->city(),
            'attachments' => [],
            'meta' => [],
        ];
    }
}

