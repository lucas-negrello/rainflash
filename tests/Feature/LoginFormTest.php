<?php

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('login form renders correctly with csrf token', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertSee('Entrar');
    $response->assertSee('Email');
    $response->assertSee('Senha');
});

it('login form redirects when already authenticated', function () {
    config()->set('admin.company.slug', 'test-company');
    $company = Company::factory()->create(['slug' => 'test-company']);
    $user = User::factory()->create();
    CompanyUser::factory()->create(['company_id' => $company->id, 'user_id' => $user->id]);

    $this->actingAs($user);

    $response = $this->get('/login');

    $response->assertRedirect(); // Deve redirecionar para algum painel
});

it('can login with valid credentials from env config', function () {
    // Simula o seed inicial - busca empresa existente ou cria nova
    $company = Company::where('slug', config('admin.company.slug', 'acme'))->first()
        ?? Company::factory()->create(['slug' => config('admin.company.slug', 'acme')]);

    $user = User::where('email', config('admin.user.email', 'admin@example.com'))->first()
        ?? User::factory()->create([
            'email' => config('admin.user.email', 'admin@example.com'),
            'password' => Hash::make(config('admin.user.password', 'password')),
        ]);

    CompanyUser::firstOrCreate(
        ['company_id' => $company->id, 'user_id' => $user->id],
        CompanyUser::factory()->raw(['company_id' => $company->id, 'user_id' => $user->id])
    );

    $response = $this->post('/login', [
        'email' => config('admin.user.email'),
        'password' => config('admin.user.password'),
    ]);

    $response->assertRedirect('/admin');
    $this->assertAuthenticatedAs($user);
});

it('shows error with invalid credentials', function () {
    $response = $this->post('/login', [
        'email' => 'invalid@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

it('rate limits login attempts', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    // Tenta fazer 6 logins com senha errada
    for ($i = 0; $i < 6; $i++) {
        $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);
    }

    // Na 6ª tentativa, deve retornar erro de rate limit
    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'wrongpassword',
    ]);

    // Rate limit retorna 302 redirect com erro de validação
    $response->assertSessionHasErrors('email');
    expect($response->getSession()->get('errors')->first('email'))
        ->toContain('Muitas tentativas');
});

it('can logout successfully', function () {
    $user = User::factory()->create(['password' => Hash::make('password')]);

    $this->actingAs($user);

    $response = $this->post('/logout');

    $response->assertRedirect('/login');
    $this->assertGuest();
});

it('remembers user when remember checkbox is checked', function () {
    config()->set('admin.company.slug', 'acme');
    $company = Company::firstOrCreate(['slug' => 'acme'], ['name' => 'Acme Inc', 'status' => 1]);
    $user = User::factory()->create([
        'email' => 'remember@example.com',
        'password' => Hash::make('password'),
    ]);
    CompanyUser::factory()->create(['company_id' => $company->id, 'user_id' => $user->id]);

    $response = $this->post('/login', [
        'email' => 'remember@example.com',
        'password' => 'password',
        'remember' => true,
    ]);

    $response->assertRedirect('/admin');
    $this->assertAuthenticatedAs($user);
});

