<?php

use App\Enums\RoleScopeEnum;
use App\Models\{Permission, Role};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Exceptions\RoleAlreadyExists;

uses(RefreshDatabase::class);

describe('RoleMethods trait', function () {
    it('finds role by key', function () {
        $role = Role::factory()->create(['key' => 'test-role']);

        $found = Role::findByName('test-role', 'web');

        expect($found->id)->toBe($role->id);
    });

    it('finds role by name', function () {
        $role = Role::factory()->create(['name' => 'Test Role']);

        $found = Role::findByName('Test Role', 'web');

        expect($found->id)->toBe($role->id);
    });

    it('throws exception when role not found by name', function () {
        Role::findByName('non-existent-role', 'web');
    })->throws(RoleDoesNotExist::class);

    it('finds role by id', function () {
        $role = Role::factory()->create();

        $found = Role::findById($role->id, 'web');

        expect($found->id)->toBe($role->id);
    });

    it('throws exception when role not found by id', function () {
        Role::findById(9999, 'web');
    })->throws(RoleDoesNotExist::class);

    it('finds or creates role', function () {
        $initialCount = Role::count();

        $role = Role::findOrCreate('new-role', 'web');

        expect(Role::count())->toBe($initialCount + 1)
            ->and($role->key)->toBe('new-role')
            ->and($role->name)->toBe('new-role')
            ->and($role->scope)->toBe(RoleScopeEnum::GLOBAL);
    });

    it('finds existing role instead of creating', function () {
        $existing = Role::factory()->create(['key' => 'existing-role']);
        $initialCount = Role::count();

        $found = Role::findOrCreate('existing-role', 'web');

        expect(Role::count())->toBe($initialCount)
            ->and($found->id)->toBe($existing->id);
    });

    it('finds existing role when slug matches', function () {
        $existing = Role::factory()->create(['key' => 'same-key', 'name' => 'Same Key']);
        $initialCount = Role::count();

        $found = Role::findOrCreate('same-key', 'web');

        expect(Role::count())->toBe($initialCount)
            ->and($found->id)->toBe($existing->id);
    });

    it('throws exception when key and scope already exist', function () {
        Role::factory()->create(['key' => 'admin-role', 'name' => 'Admin', 'scope' => RoleScopeEnum::GLOBAL]);

        // Tentar criar outro role que gera mesmo slug e scope
        Role::findOrCreate('admin role', 'web'); // gera 'admin-role' com scope GLOBAL
    })->throws(\Spatie\Permission\Exceptions\RoleAlreadyExists::class);


    it('checks if role has permission by string', function () {
        $role = Role::factory()->create();
        $permission = Permission::factory()->create(['key' => 'test-permission']);

        $role->permissions()->attach($permission->id);

        expect($role->hasPermissionTo('test-permission', 'web'))->toBeTrue()
            ->and($role->hasPermissionTo('non-existent', 'web'))->toBeFalse();
    });

    it('checks if role has permission by name', function () {
        $role = Role::factory()->create();
        $permission = Permission::factory()->create(['name' => 'Test Permission']);

        $role->permissions()->attach($permission->id);

        expect($role->hasPermissionTo('Test Permission', 'web'))->toBeTrue();
    });

    it('checks if role has permission by instance', function () {
        $role = Role::factory()->create();
        $permission = Permission::factory()->create();
        $otherPermission = Permission::factory()->create();

        $role->permissions()->attach($permission->id);

        expect($role->hasPermissionTo($permission, 'web'))->toBeTrue()
            ->and($role->hasPermissionTo($otherPermission, 'web'))->toBeFalse();
    });

    it('returns false for invalid permission type', function () {
        $role = Role::factory()->create();

        expect($role->hasPermissionTo(123, 'web'))->toBeFalse()
            ->and($role->hasPermissionTo(null, 'web'))->toBeFalse();
    });
});

describe('Role relationships', function () {
    it('has many permissions through pivot', function () {
        $role = Role::factory()->create();
        $perm1 = Permission::factory()->create();
        $perm2 = Permission::factory()->create();

        $role->permissions()->attach([$perm1->id, $perm2->id]);

        expect($role->permissions()->count())->toBe(2)
            ->and($role->permissions->pluck('id')->toArray())->toContain($perm1->id, $perm2->id);
    });
});

