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
        if (!config('admin.enabled')) {
            return;
        }

        DB::transaction(function () {
            // 1) Permissions
            $perms = [
                ['key' => 'view_sensitive_rate', 'name' => 'Ver custos/hora', 'description' => 'Permite visualizar RateHistory.hour_cost'],
                ['key' => 'manage_rates', 'name' => 'Gerenciar custos/hora', 'description' => 'Criar/editar histórico de rates'],
                ['key' => 'manage_pto', 'name' => 'Gerenciar PTO', 'description' => 'Aprovar/rejeitar PTO'],
                ['key' => 'export_reports', 'name' => 'Exportar relatórios', 'description' => 'Gerar e baixar relatórios'],
                ['key' => 'impersonate_user', 'name' => 'Impersonar usuário', 'description' => 'Iniciar sessão de impersonação'],
                ['key' => 'manage_projects', 'name' => 'Gerenciar projetos', 'description' => 'Criar/editar projetos'],
                ['key' => 'log_time', 'name' => 'Apontar horas', 'description' => 'Criar apontamentos de horas'],
            ];

            foreach ($perms as $p) {
                Permission::query()->firstOrCreate(
                    ['key' => $p['key']],
                    ['name' => $p['name'], 'description' => $p['description'], 'meta' => []]
                );
            }

            // 2) Roles
            $roles = [
                ['key' => 'ceo', 'name' => 'CEO', 'scope' => RoleScopeEnum::COMPANY],
                ['key' => 'manager', 'name' => 'Manager', 'scope' => RoleScopeEnum::COMPANY],
                ['key' => 'finance', 'name' => 'Financeiro', 'scope' => RoleScopeEnum::COMPANY],
                ['key' => 'developer', 'name' => 'Desenvolvedor', 'scope' => RoleScopeEnum::COMPANY],
            ];

            $roleIds = [];
            foreach ($roles as $r) {
                $role = Role::query()->firstOrCreate(
                    ['key' => $r['key'], 'scope' => $r['scope']],
                    ['name' => $r['name'], 'description' => null, 'meta' => []]
                );
                $roleIds[$r['key']] = $role->getKey();
            }

            // 3) Vincular permissões a roles conforme base fornecida
            $matrix = [
                'ceo' => ['view_sensitive_rate','manage_rates','manage_pto','export_reports','manage_projects','log_time','impersonate_user'],
                'manager' => ['view_sensitive_rate','manage_pto','export_reports','manage_projects','log_time'],
                'finance' => ['view_sensitive_rate','export_reports'],
                'developer' => ['log_time'],
            ];

            $permsByKey = Permission::query()->pluck('id', 'key');
            foreach ($matrix as $roleKey => $permKeys) {
                $roleId = $roleIds[$roleKey] ?? null;
                if (!$roleId) { continue; }
                $ids = collect($permKeys)->map(fn($k) => $permsByKey[$k] ?? null)->filter()->values()->all();
                if ($ids) {
                    DB::table('role_permissions')->upsert(
                        array_map(fn($pid) => ['role_id' => $roleId, 'permission_id' => $pid], $ids),
                        ['role_id','permission_id']
                    );
                }
            }

            // 4) Criar empresa e admin user/company_user e dar full access
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

            $companyUser = CompanyUser::query()->firstOrCreate(
                ['company_id' => $company->getKey(), 'user_id' => $user->getKey()],
                [
                    'primary_title' => 'Administrator',
                    'currency' => config('admin.company.currency'),
                    'active' => true,
                    'joined_at' => now(),
                    'meta' => ['seeded_admin' => true],
                ]
            );

            // role admin configurable
            $adminRoleKey = config('admin.admin_role_key', 'ceo');
            if (isset($roleIds[$adminRoleKey])) {
                DB::table('company_user_roles')->upsert([
                    ['company_user_id' => $companyUser->getKey(), 'role_id' => $roleIds[$adminRoleKey]],
                ], ['company_user_id','role_id']);
            }

            // opcional: garantir todas as permissões para role admin
            if (config('admin.grant_all_permissions_to_admin_role', true) && isset($roleIds[$adminRoleKey])) {
                $allPermIds = Permission::query()->pluck('id')->all();
                DB::table('role_permissions')->upsert(
                    array_map(fn($pid) => ['role_id' => $roleIds[$adminRoleKey], 'permission_id' => $pid], $allPermIds),
                    ['role_id','permission_id']
                );
            }
        });
    }

    public function down(): void
    {
        // Este seed é idempotente; no rollback não removemos dados para evitar perda acidental.
    }
};

