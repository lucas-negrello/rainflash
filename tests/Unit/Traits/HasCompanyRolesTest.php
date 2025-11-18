<?php

use App\Models\{Company, CompanyUser, Permission, Role, User};
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('HasCompanyRoles trait', function () {
    it('assigns role by key', function () {
        $companyUser = CompanyUser::factory()
            ->for(Company::factory())
            ->for(User::factory())
            ->create();
        $role = Role::factory()->create(['key' => 'test-manager-' . uniqid()]);

        $companyUser->assignRole($role->key);

        expect($companyUser->roles()->count())->toBe(1)
            ->and($companyUser->roles->first()->id)->toBe($role->id);
    });

    it('assigns role by id', function () {
        $companyUser = CompanyUser::factory()
            ->for(Company::factory())
            ->for(User::factory())
            ->create();
        $role = Role::factory()->create();

        $companyUser->assignRole($role->id);

        expect($companyUser->roles()->count())->toBe(1)
            ->and($companyUser->roles->first()->id)->toBe($role->id);
    });

    it('assigns role by instance', function () {
        $companyUser = CompanyUser::factory()
            ->for(Company::factory())
            ->for(User::factory())
            ->create();
        $role = Role::factory()->create();

        $companyUser->assignRole($role);

        expect($companyUser->roles()->count())->toBe(1)
            ->and($companyUser->roles->first()->id)->toBe($role->id);
    });

    it('does not duplicate roles on assign', function () {
        $companyUser = CompanyUser::factory()
            ->for(Company::factory())
            ->for(User::factory())
            ->create();
        $role = Role::factory()->create();

        $companyUser->assignRole($role);
        $companyUser->assignRole($role);

        expect($companyUser->roles()->count())->toBe(1);
    });

    it('removes role by key', function () {
        $companyUser = CompanyUser::factory()
            ->for(Company::factory())
            ->for(User::factory())
            ->create();
        $role = Role::factory()->create(['key' => 'test-remove-' . uniqid()]);
        $companyUser->roles()->attach($role->id);

        $companyUser->removeRole($role->key);

        expect($companyUser->roles()->count())->toBe(0);
    });

    it('removes role by id', function () {
        $companyUser = CompanyUser::factory()
            ->for(Company::factory())
            ->for(User::factory())
            ->create();
        $role = Role::factory()->create();
        $companyUser->roles()->attach($role->id);

        $companyUser->removeRole($role->id);

        expect($companyUser->roles()->count())->toBe(0);
    });

    it('removes role by instance', function () {
        $companyUser = CompanyUser::factory()
            ->for(Company::factory())
            ->for(User::factory())
            ->create();
        $role = Role::factory()->create();
        $companyUser->roles()->attach($role->id);

        $companyUser->removeRole($role);

        expect($companyUser->roles()->count())->toBe(0);
    });

    it('checks if has role by key', function () {
        $companyUser = CompanyUser::factory()
            ->for(Company::factory())
            ->for(User::factory())
            ->create();
        $role = Role::factory()->create(['key' => 'admin']);
        $companyUser->roles()->attach($role->id);

        expect($companyUser->hasRole('admin'))->toBeTrue()
            ->and($companyUser->hasRole('manager'))->toBeFalse();
    });

    it('checks if has role by name', function () {
        $companyUser = CompanyUser::factory()
            ->for(Company::factory())
            ->for(User::factory())
            ->create();
        $role = Role::factory()->create(['name' => 'Administrator']);
        $companyUser->roles()->attach($role->id);

        expect($companyUser->hasRole('Administrator'))->toBeTrue();
    });

    it('checks if has role by id', function () {
        $companyUser = CompanyUser::factory()
            ->for(Company::factory())
            ->for(User::factory())
            ->create();
        $role = Role::factory()->create();
        $companyUser->roles()->attach($role->id);

        expect($companyUser->hasRole($role->id))->toBeTrue()
            ->and($companyUser->hasRole(9999))->toBeFalse();
    });

    it('checks if has role by instance', function () {
        $companyUser = CompanyUser::factory()
            ->for(Company::factory())
            ->for(User::factory())
            ->create();
        $role = Role::factory()->create();
        $otherRole = Role::factory()->create();
        $companyUser->roles()->attach($role->id);

        expect($companyUser->hasRole($role))->toBeTrue()
            ->and($companyUser->hasRole($otherRole))->toBeFalse();
    });

    it('gets role names', function () {
        $companyUser = CompanyUser::factory()
            ->for(Company::factory())
            ->for(User::factory())
            ->create();
        $role1 = Role::factory()->create(['name' => 'Admin']);
        $role2 = Role::factory()->create(['name' => 'Manager']);
        $companyUser->roles()->attach([$role1->id, $role2->id]);

        $names = $companyUser->getRoleNames();

        expect($names)->toHaveCount(2)
            ->and($names->toArray())->toContain('Admin', 'Manager');
    });

    it('checks permission via role', function () {
        $companyUser = CompanyUser::factory()
            ->for(Company::factory())
            ->for(User::factory())
            ->create();
        $role = Role::factory()->create();
        $permission = Permission::factory()->create(['key' => 'edit-project']);

        $role->permissions()->attach($permission->id);
        $companyUser->roles()->attach($role->id);

        expect($companyUser->can('edit-project'))->toBeTrue()
            ->and($companyUser->can('delete-project'))->toBeFalse();
    });

    it('checks permission by name via role', function () {
        $companyUser = CompanyUser::factory()
            ->for(Company::factory())
            ->for(User::factory())
            ->create();
        $role = Role::factory()->create();
        $permission = Permission::factory()->create(['name' => 'Edit Project']);

        $role->permissions()->attach($permission->id);
        $companyUser->roles()->attach($role->id);

        expect($companyUser->can('Edit Project'))->toBeTrue();
    });

    it('checks permission across multiple roles', function () {
        $companyUser = CompanyUser::factory()
            ->for(Company::factory())
            ->for(User::factory())
            ->create();
        $role1 = Role::factory()->create();
        $role2 = Role::factory()->create();
        $perm1 = Permission::factory()->create(['key' => 'view-reports']);
        $perm2 = Permission::factory()->create(['key' => 'edit-users']);

        $role1->permissions()->attach($perm1->id);
        $role2->permissions()->attach($perm2->id);
        $companyUser->roles()->attach([$role1->id, $role2->id]);

        expect($companyUser->can('view-reports'))->toBeTrue()
            ->and($companyUser->can('edit-users'))->toBeTrue()
            ->and($companyUser->can('delete-company'))->toBeFalse();
    });
});

