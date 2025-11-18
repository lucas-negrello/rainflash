<?php

namespace Database\Factories;

use App\Enums\{TimeEntryOriginEnum, TimeEntryStatusEnum};
use App\Models\{TimeEntry, Project, Task, CompanyUser};
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<TimeEntry> */
class TimeEntryFactory extends Factory
{
    protected $model = TimeEntry::class;

    public function definition(): array
    {
        $start = now()->subHours(fake()->numberBetween(1, 48));
        $end = $start->copy()->addMinutes(fake()->numberBetween(15, 240));
        return [
            'project_id' => Project::factory(),
            'task_id' => null,
            'created_by_company_user_id' => CompanyUser::factory(),
            'reviewed_by_company_user_id' => fake()->optional()->boolean(50) ? CompanyUser::factory() : null,
            'started_at' => $start,
            'ended_at' => $end,
            'duration_minutes' => $end->diffInMinutes($start),
            'origin' => fake()->randomElement(TimeEntryOriginEnum::cases()),
            'notes' => fake()->optional()->sentence(),
            'locked' => fake()->boolean(10),
            'status' => fake()->randomElement(TimeEntryStatusEnum::cases()),
            'approved_at' => null,
            'meta' => ['source' => 'test'],
        ];
    }

    public function withTask(): static
    {
        return $this->state(function () {
            $project = Project::factory()->create();
            $task = Task::factory()->create(['project_id' => $project->id]);
            return [
                'project_id' => $project->id,
                'task_id' => $task->id,
            ];
        });
    }
}
