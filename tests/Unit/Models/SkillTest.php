<?php

use App\Models\{Skill, User};

it('casts and relation on Skill', function () {
    $skill = Skill::factory()->create(['meta' => ['k' => 'v']]);
    $user = User::factory()->create();

    $user->skills()->attach($skill->id, [
        'proficiency_level' => 3,
        'years_of_experience' => 2,
        'last_used_at' => now(),
    ]);

    expect($skill->meta)->toBeArray()
        ->and($skill->users()->count())->toBe(1);
});

