<?php

use App\Enums\RoleScopeEnum;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $company = Company::query()->firstOrCreate(
            ['slug' => config('admin.company.slug')],
            ['name' => config('admin.company.name'), 'meta' => ['currency' => config('admin.company.currency')]]
        );

        $user = User::query()->firstOrCreate(
            ['email' => config('admin.user.email')],
            [
                'name' => config('admin.user.name'),
                'password' => Hash::make(config('admin.user.password')),
                'locale' => config('admin.user.locale'),
                'timezone' => config('admin.user.timezone'),
                'meta' => ['seeded_admin' => true],
            ]
        );

        CompanyUser::query()->firstOrCreate(
            ['company_id' => $company->getKey(), 'user_id' => $user->getKey()],
            [
                'primary_title' => 'Administrator',
                'currency' => config('admin.company.currency'),
                'active' => true,
                'joined_at' => now(),
                'meta' => ['seeded_admin' => true],
            ]
        );
    }

    public function down(): void
    {
        // Este seed é idempotente; no rollback não removemos dados para evitar perda acidental.
    }
};

