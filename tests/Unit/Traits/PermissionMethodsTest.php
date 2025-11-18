<?php

use App\Models\{CompanyUser, Permission, Role};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;

uses(RefreshDatabase::class);

describe('PermissionMethods trait', function () {
    it('finds permission by key', function () {
        $permission = Permission::factory()->create(['key' => 'test-permission']);

        $found = Permission::findByName('test-permission', 'web');

        expect($found->id)->toBe($permission->id);
    });

    it('finds permission by name', function () {
        $permission = Permission::factory()->create(['name' => 'Test Permission']);

        $found = Permission::findByName('Test Permission', 'web');

        expect($found->id)->toBe($permission->id);
    });

    it('throws exception when permission not found by name', function () {
        Permission::findByName('non-existent', 'web');
    })->throws(PermissionDoesNotExist::class);

    it('finds permission by id', function () {
        $permission = Permission::factory()->create();

        $found = Permission::findById($permission->id, 'web');

        expect($found->id)->toBe($permission->id);
    });

    it('throws exception when permission not found by id', function () {
        Permission::findById(9999, 'web');
    })->throws(PermissionDoesNotExist::class);

    it('finds or creates permission', function () {
        $initialCount = Permission::count();

        $permission = Permission::findOrCreate('new-permission', 'web');

        expect(Permission::count())->toBe($initialCount + 1)
            ->and($permission->key)->toBe('new-permission')
            ->and($permission->name)->toBe('new-permission');
    });

    it('finds existing permission instead of creating', function () {
        $existing = Permission::factory()->create(['key' => 'existing-perm']);
        $initialCount = Permission::count();

        $found = Permission::findOrCreate('existing-perm', 'web');

        expect(Permission::count())->toBe($initialCount)
            ->and($found->id)->toBe($existing->id);
    });

    it('finds existing permission when slug matches', function () {
        $existing = Permission::factory()->create(['key' => 'same-key', 'name' => 'Same Key']);
        $initialCount = Permission::count();

        $found = Permission::findOrCreate('same-key', 'web');

        expect(Permission::count())->toBe($initialCount)
            ->and($found->id)->toBe($existing->id);
    });
});

describe('Permission relationships', function () {
    it('has many roles through pivot', function () {
        $permission = Permission::factory()->create();
        $role1 = Role::factory()->create();
        $role2 = Role::factory()->create();

        $permission->roles()->attach([$role1->id, $role2->id]);

        expect($permission->roles()->count())->toBe(2)
            ->and($permission->roles->pluck('id')->toArray())->toContain($role1->id, $role2->id);
    });
});

