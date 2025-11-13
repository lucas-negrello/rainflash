<?php

namespace App\Models;

use App\Enums\RoleScopeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/** @use HasFactory<\Database\Factories\RoleFactory> */
class Role extends Model
{
    use HasFactory;

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
        return $this->belongsToMany(CompanyUser::class, 'company_user_roles', 'company_user_id', 'role_id');
    }
}
