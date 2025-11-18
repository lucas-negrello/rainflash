<?php

use App\Models\{Assignment, Project, CompanyUser};
use Database\Factories\{AssignmentFactory};
use Illuminate\Support\Carbon;

it('creates Assignment with casts and relations', function () {
    $assignment = AssignmentFactory::new()->create();

    expect($assignment->project)->toBeInstanceOf(Project::class)
        ->and($assignment->companyUser)->toBeInstanceOf(CompanyUser::class)
        ->and($assignment->effective_from)->toBeInstanceOf(Carbon::class)
        ->and($assignment->weekly_capacity_hours)->toBeFloat()
        ->and($assignment->meta)->toBeArray();
});

