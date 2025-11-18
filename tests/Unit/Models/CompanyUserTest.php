<?php
use App\Models\{Company, CompanyUser, Role, User, WorkSchedule, RateHistory, AuditLog, ReportJob, Team, TeamMember, PtoRequest, PtoApproval, Project, Assignment, TimeEntry, Task};
use Database\Factories\{ProjectFactory, AssignmentFactory, TimeEntryFactory, TaskFactory};

it('relations on CompanyUser (company, user, roles)', function () {
    $company = Company::factory()->create();
    $user = User::factory()->create();
    $cu = CompanyUser::factory()->for($company)->for($user)->create();

    $role = Role::factory()->create();
    $cu->roles()->attach($role->id);

    expect($cu->company->is($company))->toBeTrue()
        ->and($cu->user->is($user))->toBeTrue()
        ->and($cu->roles()->count())->toBe(1);
});

it('has many WorkSchedules and RateHistory', function () {
    $cu = CompanyUser::factory()->withWorkSchedules(2)->withRateHistory(3)->create();

    expect($cu->workSchedules()->count())->toBe(2)
        ->and($cu->rateHistory()->count())->toBe(3);
});

it('casts and fillables on CompanyUser', function () {
    $joined = now()->subMonth();
    $left = now();

    $cu = CompanyUser::factory()->create([
        'active' => false,
        'joined_at' => $joined,
        'left_at' => $left,
        'meta' => ['k' => 'v'],
    ]);

    expect($cu->getFillable())
        ->toContain('company_id','user_id','primary_title','currency','active','joined_at','left_at','meta')
        ->and($cu->active)->toBeFalse()
        ->and($cu->joined_at->toDateTimeString())->toBe($joined->toDateTimeString())
        ->and($cu->left_at->toDateTimeString())->toBe($left->toDateTimeString())
        ->and($cu->meta)->toBeArray();
});

it('has auditLogs and reportJobs relations', function () {
    $company = Company::factory()->create();
    $user = User::factory()->create();
    $cu = CompanyUser::factory()->for($company)->for($user)->create();

    AuditLog::factory()->count(2)->create([
        'company_id' => $company->id,
        'actor_user_id' => $user->id,
        'actor_company_user_id' => $cu->id,
    ]);
    ReportJob::factory()->count(3)->create([
        'company_id' => $company->id,
        'requested_by_company_user_id' => $cu->id,
    ]);

    expect($cu->auditLogs()->count())->toBe(2)
        ->and($cu->reportJobs()->count())->toBe(3);
});

it('belongsToMany teams and hasMany teamMembers', function () {
    $company = Company::factory()->create();
    $cu = CompanyUser::factory()->for($company)->create();
    $teamA = Team::factory()->for($company)->create();
    $teamB = Team::factory()->for($company)->create();

    TeamMember::factory()->create(['company_user_id' => $cu->id, 'team_id' => $teamA->id]);
    TeamMember::factory()->create(['company_user_id' => $cu->id, 'team_id' => $teamB->id]);

    expect($cu->teams()->count())->toBe(2)
        ->and($cu->teamMembers()->count())->toBe(2);
});

it('has ptoRequestsAsRequirer, ptoRequestsAsApprover and ptoApprovals relations', function () {
    $company = Company::factory()->create();
    [$reqUser, $aprUser] = CompanyUser::factory()->count(2)->for($company)->create();

    // As requirer
    PtoRequest::factory()->count(2)->create([
        'company_id' => $company->id,
        'company_user_id' => $reqUser->id,
    ]);

    // As approver
    PtoRequest::factory()->count(3)->create([
        'company_id' => $company->id,
        'approved_by_company_user_id' => $aprUser->id,
    ]);

    // Approvals
    PtoApproval::factory()->count(4)->create([
        'approver_company_user_id' => $aprUser->id,
    ]);

    expect($reqUser->ptoRequestsAsRequirer()->count())->toBe(2)
        ->and($aprUser->ptoRequestsAsApprover()->count())->toBe(3)
        ->and($aprUser->ptoApprovals()->count())->toBe(4);
});

it('has assignments, time entries, and tasks relations', function () {
    $company = Company::factory()->create();
    $cu = CompanyUser::factory()->for($company)->create();

    $project = ProjectFactory::new()->create(['company_id' => $company->id]);

    AssignmentFactory::new()->create(['company_user_id' => $cu->id, 'project_id' => $project->id]);
    TimeEntryFactory::new()->create(['project_id' => $project->id, 'created_by_company_user_id' => $cu->id]);
    TimeEntryFactory::new()->create(['project_id' => $project->id, 'reviewed_by_company_user_id' => $cu->id]);
    TaskFactory::new()->create(['project_id' => $project->id, 'assignee_company_user_id' => $cu->id]);
    TaskFactory::new()->create(['project_id' => $project->id, 'created_by_company_user_id' => $cu->id]);

    expect($cu->assignments()->count())->toBe(1)
        ->and($cu->timeEntriesAsCreator()->count())->toBe(1)
        ->and($cu->timeEntriesAsReviewer()->count())->toBe(1)
        ->and($cu->tasksAsAssignee()->count())->toBe(1)
        ->and($cu->tasksAsCreator()->count())->toBe(1);
});
