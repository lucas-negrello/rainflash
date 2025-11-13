<?php

namespace Database\Factories;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Permission>
 */
class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        return [
            'key' => Str::slug($name.'-'.fake()->unique()->bothify('???')),
            'name' => Str::title($name),
            'description' => fake()->optional()->sentence(),
            'meta' => [],
        ];
    }
}

