<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

/**
 * Trait encapsulando implementação dos métodos do contrato de Permission.
 * @mixin \App\Models\Permission
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static create(array $attributes)
 */
trait PermissionMethods
{
    /** @return PermissionContract */
    public static function findByName(string $name, ?string $guardName): PermissionContract
    {
        $permission = static::query()
            ->where('key', $name)
            ->orWhere('name', $name)
            ->first();

        if (!$permission) {
            throw new PermissionDoesNotExist("There is no permission named `{$name}` for guard `{$guardName}`.");
        }

        return $permission;
    }

    /** @return PermissionContract */
    public static function findById(int|string $id, ?string $guardName): PermissionContract
    {
        $permission = static::query()->find($id);
        if (!$permission) {
            throw new PermissionDoesNotExist("There is no permission with id `{$id}` for guard `{$guardName}`.");
        }
        return $permission;
    }

    /** @return PermissionContract */
    public static function findOrCreate(string $name, ?string $guardName): PermissionContract
    {
        $existing = static::query()
            ->where('key', $name)
            ->orWhere('name', $name)
            ->first();

        if ($existing) {
            return $existing;
        }

        $data = [
            'key' => Str::slug($name),
            'name' => $name,
        ];

        // Só lança exceção se já existir com o mesmo key
        $existingByKey = static::query()->where('key', $data['key'])->first();
        if ($existingByKey && $existingByKey->name !== $name) {
            throw new PermissionAlreadyExists("A permission `{$name}` already exists for guard `{$guardName}`.");
        }

        return static::create($data);
    }
}
