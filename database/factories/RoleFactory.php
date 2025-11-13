<?php

namespace Database\Factories;

use App\Enums\RoleScopeEnum;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        $name = Str::title(fake()->unique()->words(2, true));
        return [
            'key' => Str::slug($name.'-'.fake()->unique()->bothify('###')),
            'scope' => fake()->randomElement(RoleScopeEnum::cases()),
            'name' => $name,
            'description' => fake()->optional()->sentence(),
            'meta' => [],
        ];
    }
}

