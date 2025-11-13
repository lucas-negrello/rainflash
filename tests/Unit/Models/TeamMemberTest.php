<?php

use App\Models\{Company, CompanyUser, Team, TeamMember};

it('creates TeamMember and loads relations & casts', function () {
    $company = Company::factory()->create();
    $cu = CompanyUser::factory()->for($company)->create();
    $team = Team::factory()->for($company)->create();

    $joined = now()->subDays(10);
    $left = now();

    $tm = TeamMember::factory()->create([
        'company_user_id' => $cu->id,
        'team_id' => $team->id,
        'role_in_team' => 'Member',
        'joined_at' => $joined,
        'left_at' => $left,
        'meta' => ['a' => 1],
    ]);

    expect($tm->team->is($team))->toBeTrue()
        ->and($tm->companyUser->is($cu))->toBeTrue()
        ->and($tm->joined_at->toDateTimeString())->toBe($joined->toDateTimeString())
        ->and($tm->left_at->toDateTimeString())->toBe($left->toDateTimeString())
        ->and($tm->meta)->toBeArray();
});

