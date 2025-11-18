<?php

namespace App\Models;

use App\Enums\RoleScopeEnum;
use App\Traits\RoleMethods;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Contracts\Role as RoleContract;

/** @use HasFactory<\Database\Factories\RoleFactory> */
class Role extends Model implements RoleContract
{
    use HasFactory, RoleMethods;

    protected $table = 'roles';

    protected $fillable = [
        'key',
        'scope',
        'name',
        'description',
        'meta',
    ];

    protected $casts = [
        'scope' => RoleScopeEnum::class,
        'meta' => 'array',
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }

    public function companyUsers(): BelongsToMany
    {
        return $this->belongsToMany(CompanyUser::class, 'company_user_roles', 'role_id', 'company_user_id');
    }
}
