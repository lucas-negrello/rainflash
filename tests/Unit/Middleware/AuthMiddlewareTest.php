<?php

use App\Http\Middleware\{EnsureAdminCompanyMember, EnsureHasAnyCompany};
use App\Models\{Company, CompanyUser, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

describe('EnsureAdminCompanyMember middleware', function () {
    it('redirects unauthenticated users to login', function () {
        $request = Request::create('/admin', 'GET');
        $middleware = new EnsureAdminCompanyMember();

        $response = $middleware->handle($request, fn() => response('OK'));

        expect($response->getStatusCode())->toBe(302)
            ->and($response->headers->get('Location'))->toContain('/login');
    });

    it('redirects non-admin company members to root', function () {
        config()->set('admin.company.slug', 'acme');

        $otherCompany = Company::factory()->create(['slug' => 'other-company']);
        $user = User::factory()->create();
        CompanyUser::factory()->create(['company_id' => $otherCompany->id, 'user_id' => $user->id]);

        Auth::login($user);

        $request = Request::create('/admin', 'GET');
        $middleware = new EnsureAdminCompanyMember();

        $response = $middleware->handle($request, fn() => response('OK'));

        expect($response->getStatusCode())->toBe(302)
            ->and($response->headers->get('Location'))->toContain('/');
    });

    it('allows admin company members to proceed', function () {
        config()->set('admin.company.slug', 'acme');

        $adminCompany = Company::firstOrCreate(['slug' => 'acme'], ['name' => 'Admin Inc', 'status' => 1]);
        $user = User::factory()->create();
        CompanyUser::factory()->create(['company_id' => $adminCompany->id, 'user_id' => $user->id]);

        Auth::login($user);

        $request = Request::create('/admin', 'GET');
        $middleware = new EnsureAdminCompanyMember();

        $response = $middleware->handle($request, fn() => response('OK', 200));

        expect($response->getStatusCode())->toBe(200)
            ->and($response->getContent())->toBe('OK');
    });
});

describe('EnsureHasAnyCompany middleware', function () {
    it('redirects unauthenticated users to login', function () {
        $request = Request::create('/', 'GET');
        $middleware = new EnsureHasAnyCompany();

        $response = $middleware->handle($request, fn() => response('OK'));

        expect($response->getStatusCode())->toBe(302)
            ->and($response->headers->get('Location'))->toContain('/login');
    });

    it('redirects users without companies to login', function () {
        $user = User::factory()->create();
        Auth::login($user);

        $request = Request::create('/', 'GET');
        $middleware = new EnsureHasAnyCompany();

        $response = $middleware->handle($request, fn() => response('OK'));

        expect($response->getStatusCode())->toBe(302)
            ->and($response->headers->get('Location'))->toContain('/login');
    });

    it('allows users with companies to proceed', function () {
        $company = Company::factory()->create();
        $user = User::factory()->create();
        CompanyUser::factory()->create(['company_id' => $company->id, 'user_id' => $user->id]);

        Auth::login($user);

        $request = Request::create('/', 'GET');
        $middleware = new EnsureHasAnyCompany();

        $response = $middleware->handle($request, fn() => response('OK', 200));

        expect($response->getStatusCode())->toBe(200)
            ->and($response->getContent())->toBe('OK');
    });
});

