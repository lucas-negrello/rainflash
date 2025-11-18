<?php

use App\Models\CompanyUser;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('atribui role a um company user', function () {
    $companyUser = CompanyUser::factory()->create();
    $role = Role::factory()->create();

    expect($companyUser->hasRole($role->key))->toBeFalse();

    $companyUser->assignRole($role);

    expect($companyUser->hasRole($role->key))->toBeTrue();
});

it('verifica permissao via role', function () {
    $companyUser = CompanyUser::factory()->create();
    $role = Role::factory()->create();
    $permission = Permission::factory()->create();

    // Relacionar permissao ao role
    $role->permissions()->syncWithoutDetaching([$permission->getKey()]);

    $companyUser->assignRole($role);

    expect($companyUser->can($permission->key))->toBeTrue();
    expect($companyUser->can('inexistente'))->toBeFalse();
});

