<?php

use App\Enums\UserSkillProficiencyLevelEnum;
use App\Models\{AuditLog, Company, ResourceProfile, Skill, User};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

it('casts on User and hidden attributes', function () {
    $user = User::factory()->create([
        'meta' => ['x' => 'y'],
    ]);

    // Casts should be applied
    $user->refresh();

    expect($user->getHidden())
        ->toContain('password', 'remember_token')
        ->and($user->meta)->toBeArray()
        ->and($user->email_verified_at)->toBeInstanceOf(Carbon::class);

    // Password cast (hashed) should hash on assignment
    $plain = 'new-password-123';
    $user->password = $plain;
    $user->save();

    expect(Hash::check($plain, $user->password))->toBeTrue();
});

it('relates companies and skills via pivot', function () {
    $user = User::factory()->create();
    $company = Company::factory()->create();
    $skill = Skill::factory()->create();

    $user->companies()->attach($company->id, ['active' => true]);

    $user->skills()->attach($skill->id, [
        'proficiency_level' => UserSkillProficiencyLevelEnum::HIGH,
        'years_of_experience' => 3,
        'last_used_at' => now(),
    ]);

    expect($user->companies()->count())->toBe(1)
        ->and($user->skills()->count())->toBe(1)
        ->and($user->skills()->first()->pivot->proficiency_level)
            ->toBe(UserSkillProficiencyLevelEnum::HIGH->value);
});

it('has one resourceProfile and AuditLogs as actor', function () {
    $user = User::factory()->create();
    $profile = ResourceProfile::factory()->for($user)->create();

    // user as actor in audit logs
    AuditLog::factory()->count(2)->create([
        'company_id' => Company::factory(),
        'actor_user_id' => $user->id,
        'actor_company_user_id' => null,
    ]);

    $user->refresh();
    expect($user->resourceProfile->is($profile))->toBeTrue();
});

it('fillable properties and meta cast on User', function () {
    $user = User::factory()->create([
        'locale' => 'pt_BR',
        'timezone' => 'America/Sao_Paulo',
        'meta' => ['a' => 1],
    ]);

    expect($user->getFillable())
        ->toContain('name','email','password','locale','timezone','meta')
        ->and($user->meta)->toBeArray();
});

it('auditLogs relation on User', function () {
    $user = User::factory()->create();
    $company = Company::factory()->create();

    AuditLog::factory()->count(2)->create([
        'company_id' => $company->id,
        'actor_user_id' => $user->id,
        'actor_company_user_id' => null,
    ]);

    expect($user->auditLogs()->count())->toBe(2);
});
