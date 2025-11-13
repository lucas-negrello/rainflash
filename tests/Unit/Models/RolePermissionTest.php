<?php

use App\Enums\RoleScopeEnum;
use App\Models\{Permission, Role};

it('relates roles and permissions via pivot', function () {
    $role = Role::factory()->create(['scope' => RoleScopeEnum::GLOBAL]);
    $permission = Permission::factory()->create();

    $role->permissions()->attach($permission->id);

    expect($role->permissions()->count())->toBe(1)
        ->and($permission->roles()->count())->toBe(1);
});

