<?php

use App\Enums\ResourceProfileSeniorityEnum;
use App\Models\{ResourceProfile, User};

it('casts and relation on ResourceProfile', function () {
    $user = User::factory()->create();

    $profile = ResourceProfile::factory()->create([
        'user_id' => $user->id,
        'seniority' => ResourceProfileSeniorityEnum::SENIOR,
        'attachments' => ['cv.pdf'],
        'meta' => ['bio' => 'ok'],
    ]);

    expect($profile->seniority)->toBe(ResourceProfileSeniorityEnum::SENIOR)
        ->and($profile->attachments)->toBeArray()
        ->and($profile->user->is($user))->toBeTrue();
});

