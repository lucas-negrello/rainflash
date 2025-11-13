<?php

use App\Models\{Permission, Role};

it('fillables and relation on Permission', function () {
    $perm = Permission::factory()->create(['meta' => ['abc' => 123]]);
    $role = Role::factory()->create();

    $perm->roles()->attach($role->id);

    expect($perm->getFillable())
        ->toContain('key', 'name', 'description', 'meta')
        ->and($perm->roles()->count())->toBe(1);
});

