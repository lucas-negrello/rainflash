<?php

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('seeds permissions and roles and assigns full access to admin role', function () {
    // Migrations já rodam por RefreshDatabase e executam nossa migration de seed

    // permissões essenciais
    $expectedPerms = [
        'view_sensitive_rate', 'manage_rates', 'manage_pto',
        'export_reports', 'impersonate_user', 'manage_projects', 'log_time',
    ];

    foreach ($expectedPerms as $k) {
        expect(Permission::query()->where('key', $k)->exists())->toBeTrue();
    }

    // roles
    $ceo = Role::query()->where('key', 'ceo')->first();
    $manager = Role::query()->where('key', 'manager')->first();
    $finance = Role::query()->where('key', 'finance')->first();
    $developer = Role::query()->where('key', 'developer')->first();

    expect($ceo)->not->toBeNull();
    expect($manager)->not->toBeNull();
    expect($finance)->not->toBeNull();
    expect($developer)->not->toBeNull();

    // admin full access: role admin deve ter todas as permissões
    $totalPerms = Permission::count();
    expect($ceo->permissions()->count())->toBe($totalPerms);
});

it('creates admin user and ties to company and grants admin role', function () {
    $user = User::query()->where('email', config('admin.user.email'))->first();
    expect($user)->not->toBeNull();

    $company = Company::query()->where('slug', config('admin.company.slug'))->first();
    expect($company)->not->toBeNull();

    $companyUser = CompanyUser::query()->where(['company_id' => $company->id, 'user_id' => $user->id])->first();
    expect($companyUser)->not->toBeNull();

    // Deve possuir role admin (ceo) e poder tudo o que role tem
    expect($companyUser->hasRole('ceo'))->toBeTrue();
    expect($companyUser->can('manage_projects'))->toBeTrue();
    expect($companyUser->can('export_reports'))->toBeTrue();
});

