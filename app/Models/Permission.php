<?php

namespace App\Models;

use App\Enums\PermissionScopeEnum;
use App\Traits\PermissionMethods;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Contracts\Permission as PermissionContract;

/** @use HasFactory<\Database\Factories\PermissionFactory> */
class Permission extends Model implements PermissionContract
{
    use HasFactory, PermissionMethods;

    protected $table = 'permissions';

    protected $fillable = [
        'key',
        'name',
        'scope',
        'description',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'scope' => PermissionScopeEnum::class,
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id', 'role_id');
    }

    public function scopeRoot(Builder $query): Builder
    {
        return $query->where('scope', PermissionScopeEnum::ROOT->value);
    }

    public function scopeUser(Builder $query): Builder
    {
        return $query->where('scope', PermissionScopeEnum::USER->value);
    }
}
