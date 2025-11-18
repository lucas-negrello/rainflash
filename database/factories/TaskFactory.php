<?php

namespace Database\Factories;

use App\Enums\{TaskStatusEnum, TaskTypeEnum};
use App\Models\{Task, Project, CompanyUser};
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Task> */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(TaskStatusEnum::cases()),
            'type' => fake()->randomElement(TaskTypeEnum::cases()),
            'estimated_minutes' => fake()->optional()->numberBetween(15, 8*60),
            'assignee_company_user_id' => fake()->optional()->boolean(70) ? CompanyUser::factory() : null,
            'created_by_company_user_id' => CompanyUser::factory(),
            'meta' => ['priority' => fake()->randomElement(['low','medium','high'])],
        ];
    }
}

