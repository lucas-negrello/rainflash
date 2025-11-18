<?php

namespace Database\Factories;

use App\Enums\{ProjectBillingModelEnum, ProjectStatusEnum, ProjectTypeEnum};
use App\Models\{Project, Company};
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Project> */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'code' => fake()->optional()->bothify('PROJ-####'),
            'name' => fake()->sentence(3),
            'type' => fake()->randomElement(ProjectTypeEnum::cases()),
            'billing_model' => fake()->randomElement(ProjectBillingModelEnum::cases()),
            'status' => fake()->randomElement(ProjectStatusEnum::cases()),
            'meta' => ['color' => fake()->safeColorName()],
        ];
    }
}
