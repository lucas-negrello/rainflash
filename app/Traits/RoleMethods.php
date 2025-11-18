<?php

namespace App\Traits;

use App\Enums\RoleScopeEnum;
use App\Models\Permission;
use Illuminate\Support\Str;
use Spatie\Permission\Contracts\Role as RoleContract;
use Spatie\Permission\Exceptions\RoleAlreadyExists;
use Spatie\Permission\Exceptions\RoleDoesNotExist;

/**
 * Trait encapsulando implementação dos métodos do contrato de Role.
 * @mixin \App\Models\Role
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static create(array $attributes)
 */
trait RoleMethods
{
    /** @return RoleContract */
    public static function findByName(string $name, ?string $guardName): RoleContract
    {
        $role = static::query()
            ->where('key', $name)
            ->orWhere('name', $name)
            ->first();

        if (!$role) {
            throw new RoleDoesNotExist("There is no role named `{$name}` for guard `{$guardName}`.");
        }

        return $role; // @phpstan-ignore-line
    }

    /** @return RoleContract */
    public static function findById(int|string $id, ?string $guardName): RoleContract
    {
        $role = static::query()->find($id);
        if (!$role) {
            throw new RoleDoesNotExist("There is no role with id `{$id}` for guard `{$guardName}`.");
        }
        return $role; // @phpstan-ignore-line
    }

    /** @return RoleContract */
    public static function findOrCreate(string $name, ?string $guardName): RoleContract
    {
        $existing = static::query()
            ->where('key', $name)
            ->orWhere('name', $name)
            ->first();
        if ($existing) {
            return $existing; // @phpstan-ignore-line
        }

        $data = [
            'key' => Str::slug($name),
            'name' => $name,
            'scope' => RoleScopeEnum::GLOBAL,
        ];

        if (static::query()->where('key', $data['key'])->where('scope', $data['scope'])->exists()) {
            throw new RoleAlreadyExists("A role `{$name}` already exists for guard `{$guardName}`.");
        }

        return static::create($data); // @phpstan-ignore-line
    }

    public function hasPermissionTo($permission, ?string $guardName): bool
    {
        if (is_string($permission)) {
            return $this->permissions()
                ->where(function ($q) use ($permission) {
                    $q->where('permissions.key', $permission)
                      ->orWhere('permissions.name', $permission);
                })
                ->exists();
        }

        if ($permission instanceof Permission) {
            return $this->permissions()->where('permissions.id', $permission->getKey())->exists();
        }

        return false;
    }
}
