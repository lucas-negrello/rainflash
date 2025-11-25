<?php

use App\Enums\RoleScopeEnum;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $adminCompany = Company::query()->firstOrCreate(
            ['slug' => config('admin.company.slug')],
            ['name' => config('admin.company.name'), 'meta' => ['currency' => config('admin.company.currency')]]
        );

        $adminUser = User::query()->firstOrCreate(
            ['email' => config('admin.user.email')],
            [
                'name' => config('admin.user.name'),
                'password' => Hash::make(config('admin.user.password')),
                'locale' => config('admin.user.locale'),
                'timezone' => config('admin.user.timezone'),
                'meta' => ['seeded_admin' => true],
            ]
        );

        $adminCompanyUser = CompanyUser::query()->firstOrCreate(
            ['company_id' => $adminCompany->getKey(), 'user_id' => $adminUser->getKey()],
            [
                'primary_title' => 'Administrator',
                'currency' => config('admin.company.currency'),
                'active' => true,
                'joined_at' => now(),
                'meta' => ['seeded_admin' => true],
            ]
        );

        $adminRoles = Role::global()->get();
        $adminPermissions = Permission::root()->get();

        $companyRoles = Role::company()->get();
        $userPermissions = Permission::user()->get();

        // Assign permissions to roles
        DB::transaction(function () use ($adminRoles, $adminPermissions, $companyRoles, $userPermissions) {
            foreach ($adminRoles as $role) {
                $role->permissions()->syncWithoutDetaching($adminPermissions->pluck('id')->toArray());
            }

            foreach ($companyRoles as $role) {
                $role->permissions()->syncWithoutDetaching($userPermissions->pluck('id')->toArray());
            }
        });

        // Assign roles to the initial admin user
        DB::transaction(function () use ($adminCompanyUser, $adminRoles) {
            $adminCompanyUser->roles()->syncWithoutDetaching($adminRoles->pluck('id')->toArray());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This seed is idempotent; on rollback we do not remove data to avoid accidental loss.
    }
};
