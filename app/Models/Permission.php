<?php

namespace App\Models;

use App\Traits\PermissionMethods;
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
        'description',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id', 'role_id');
    }
}
