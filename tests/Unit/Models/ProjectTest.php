<?php

use App\Models\{Project, Company, Task, Assignment, TimeEntry};
use Database\Factories\{ProjectFactory, CompanyFactory, TaskFactory, AssignmentFactory, TimeEntryFactory};

it('creates Project with casts and relations', function () {
    $project = ProjectFactory::new()->create();

    TaskFactory::new()->count(2)->create(['project_id' => $project->id]);
    AssignmentFactory::new()->count(2)->create(['project_id' => $project->id]);
    TimeEntryFactory::new()->count(2)->create(['project_id' => $project->id]);

    expect($project->company)->toBeInstanceOf(Company::class)
        ->and($project->tasks()->count())->toBe(2)
        ->and($project->assignments()->count())->toBe(2)
        ->and($project->timeEntries()->count())->toBe(2)
        ->and($project->meta)->toBeArray();
});

