<?php

namespace App\Traits;

use App\Models\Role;
use Illuminate\Support\Collection;

/**
 * Trait que fornece API de roles & permissions para CompanyUser.
 * @mixin \App\Models\CompanyUser
 * @method roles() \Illuminate\Database\Eloquent\Relations\BelongsToMany
 */
trait HasCompanyRoles
{
    public function assignRole(string|int|Role $role): static
    {
        $roleModel = $role instanceof Role ? $role : Role::query()
            ->when(is_int($role), fn($q) => $q->whereKey($role))
            ->when(is_string($role), fn($q) => $q->where('key', $role)->orWhere('name', $role))
            ->firstOrFail();
        $this->roles()->syncWithoutDetaching([$roleModel->getKey()]);
        app('cache')->forget(config('permission.cache.key'));
        return $this;
    }

    public function removeRole(string|int|Role $role): static
    {
        if ($role instanceof Role) {
            $roleId = $role->getKey();
        } elseif (is_int($role)) {
            $roleId = $role;
        } else {
            $roleModel = Role::query()
                ->where(function ($q) use ($role) {
                    $q->where('key', $role)->orWhere('name', $role);
                })
                ->first();
            $roleId = $roleModel?->getKey();
        }

        if ($roleId) {
            $this->roles()->detach([$roleId]);
            app('cache')->forget(config('permission.cache.key'));
        }
        return $this;
    }

    public function hasRole(string|int|Role $role): bool
    {
        if ($role instanceof Role) {
            return $this->roles()->whereKey($role->getKey())->exists();
        }
        if (is_int($role)) {
            return $this->roles()->whereKey($role)->exists();
        }
        return $this->roles()->where('roles.key', $role)->orWhere('roles.name', $role)->exists();
    }

    public function getRoleNames(): Collection
    {
        return $this->roles()->pluck('roles.name');
    }

    public function can($permission, $guardName = null): bool
    {
        return $this->roles()->whereHas('permissions', function ($q) use ($permission) {
            $q->where('permissions.key', $permission)->orWhere('permissions.name', $permission);
        })->exists();
    }
}
