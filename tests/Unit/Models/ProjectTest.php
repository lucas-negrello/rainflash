<?php

use App\Models\{Project, Company, Task, Assignment, TimeEntry, CompanyUser};
use Database\Factories\{ProjectFactory, CompanyFactory, TaskFactory, AssignmentFactory, TimeEntryFactory, CompanyUserFactory};

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

it('relates companyUsers via assignments pivot', function () {
    $project = ProjectFactory::new()->create();
    $cu = CompanyUserFactory::new()->create(['company_id' => $project->company_id]);
    AssignmentFactory::new()->create(['project_id' => $project->id, 'company_user_id' => $cu->id]);

    expect($project->companyUsers()->count())->toBe(1)
        ->and($project->companyUsers()->first())->toBeInstanceOf(CompanyUser::class);
});
