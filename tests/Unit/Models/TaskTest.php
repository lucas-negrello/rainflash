<?php

use App\Models\{Task, Project, CompanyUser, TimeEntry};
use Database\Factories\{TaskFactory, ProjectFactory, CompanyUserFactory, TimeEntryFactory};

it('creates Task with casts and relations', function () {
    $task = TaskFactory::new()->create();

    TimeEntryFactory::new()->count(2)->create(['project_id' => $task->project_id, 'task_id' => $task->id]);

    $assignee = $task->companyUserAssignee;

    expect($task->project)->toBeInstanceOf(Project::class)
        ->and($task->companyUserCreator)->toBeInstanceOf(CompanyUser::class)
        ->and(($assignee === null || $assignee instanceof CompanyUser))->toBeTrue()
        ->and($task->timeEntries()->count())->toBe(2)
        ->and($task->meta)->toBeArray();
});
