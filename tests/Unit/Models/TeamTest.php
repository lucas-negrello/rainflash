<?php

use App\Models\{Company, CompanyUser, Team, TeamMember};

it('creates Team and loads relations', function () {
    $company = Company::factory()->create();
    $team = Team::factory()->create(['company_id' => $company->id]);

    $cu1 = CompanyUser::factory()->for($company)->create();
    $cu2 = CompanyUser::factory()->for($company)->create();

    TeamMember::factory()->create(['team_id' => $team->id, 'company_user_id' => $cu1->id]);
    TeamMember::factory()->create(['team_id' => $team->id, 'company_user_id' => $cu2->id]);

    expect($team->company->is($company))->toBeTrue()
        ->and($team->teamMembers()->count())->toBe(2)
        ->and($team->companyUsers()->count())->toBe(2);
});

