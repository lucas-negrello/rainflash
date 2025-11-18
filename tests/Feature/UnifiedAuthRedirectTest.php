<?php

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

function createUserWithCompany(string $slug): array {
    $company = Company::factory()->create(['slug' => $slug]);
    $user = User::factory()->create(['password' => Hash::make('password')]);
    $cu = CompanyUser::factory()->create(['company_id' => $company->id, 'user_id' => $user->id]);
    return [$user, $company, $cu];
}

it('redirects admin-company member to /admin after login', function () {
    // garantir slug de empresa admin pela config
    config()->set('admin.company.slug', 'acme');

    [$user] = createUserWithCompany('acme');

    $resp = $this->post('/login', ['email' => $user->email, 'password' => 'password']);
    $resp->assertRedirect('/admin');
});

it('redirects regular user (non-admin-company) to / after login', function () {
    config()->set('admin.company.slug', 'acme');

    [$user] = createUserWithCompany('otherco');

    $resp = $this->post('/login', ['email' => $user->email, 'password' => 'password']);
    $resp->assertRedirect('/');
});

it('blocks access to /admin for non-admin-company member', function () {
    config()->set('admin.company.slug', 'acme');
    [$user] = createUserWithCompany('otherco');

    $this->actingAs($user);
    $this->get('/admin')->assertRedirect('/');
});

it('blocks access to / if user has no company', function () {
    $user = User::factory()->create(['password' => Hash::make('password')]);

    $this->actingAs($user);
    $this->get('/')->assertRedirect('/login');
});

