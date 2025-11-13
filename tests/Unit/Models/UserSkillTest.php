<?php

use App\Enums\UserSkillProficiencyLevelEnum;
use App\Models\{Skill, User, UserSkill};

it('casts and relations on UserSkill', function () {
    $user = User::factory()->create();
    $skill = Skill::factory()->create();

    $us = UserSkill::factory()->for($user)->for($skill, 'skill')->create([
        'proficiency_level' => UserSkillProficiencyLevelEnum::MEDIUM,
        'years_of_experience' => 4,
    ]);

    expect($us->proficiency_level)->toBe(UserSkillProficiencyLevelEnum::MEDIUM)
        ->and($us->user->is($user))->toBeTrue()
        ->and($us->skill->is($skill))->toBeTrue();
});

