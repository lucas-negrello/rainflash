<?php

use App\Models\{AuditLog, Company, CompanyUser, User};

it('creates AuditLog and loads relations', function () {
    $company = Company::factory()->create();
    $user = User::factory()->create();
    $companyUser = CompanyUser::factory()->for($company)->for($user)->create();

    $log = AuditLog::factory()->create([
        'company_id' => $company->id,
        'actor_user_id' => $user->id,
        'actor_company_user_id' => $companyUser->id,
        'action_key' => 'user.created',
        'subject_type' => User::class,
        'subject_id' => $user->id,
        'meta' => ['ip' => '127.0.0.1'],
    ]);

    expect($log->company->is($company))->toBeTrue()
        ->and($log->actorUser->is($user))->toBeTrue()
        ->and($log->actorCompanyUser->is($companyUser))->toBeTrue();
});

