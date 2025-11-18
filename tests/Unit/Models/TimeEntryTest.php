<?php

use App\Models\{TimeEntry, Project, Task, CompanyUser};
use Database\Factories\{TimeEntryFactory};
use Illuminate\Support\Carbon;

it('creates TimeEntry with casts and relations (no task)', function () {
    $entry = TimeEntryFactory::new()->create();

    $reviewer = $entry->companyUserReviewer;

    expect($entry->project)->toBeInstanceOf(Project::class)
        ->and($entry->task)->toBeNull()
        ->and($entry->companyUserCreator)->toBeInstanceOf(CompanyUser::class)
        ->and(($reviewer === null || $reviewer instanceof CompanyUser))->toBeTrue()
        ->and($entry->started_at)->toBeInstanceOf(Carbon::class)
        ->and($entry->ended_at)->toBeInstanceOf(Carbon::class)
        ->and($entry->duration_minutes)->toBeInt()
        ->and($entry->meta)->toBeArray();
});

it('creates TimeEntry with task matching project via state', function () {
    $entry = TimeEntryFactory::new()->withTask()->create();

    expect($entry->task)->toBeInstanceOf(Task::class)
        ->and($entry->task->project_id)->toBe($entry->project_id);
});
