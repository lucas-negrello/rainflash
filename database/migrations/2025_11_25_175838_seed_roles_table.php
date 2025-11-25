<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $globalRoles = [
            [
                'name'          => 'Administrador',
                'key'           => 'admin',
                'description'   => 'Papel com permissões administrativas completas no sistema.',
            ]
        ];

        $companyRoles = [
            [
                'name'          => 'CEO',
                'key'           => 'ceo',
                'description'   => 'Papel para o CEO da empresa, com permissões amplas.',
            ],
            [
                'name'          => 'Financeiro',
                'key'           => 'finance',
                'description'   => 'Papel para o departamento financeiro, com acesso a funcionalidades financeiras.',
            ],
            [
                'name'          => 'Pessoas e Cultura',
                'key'           => 'hr',
                'description'   => 'Papel para o departamento de Pessoas e Cultura, com acesso a funcionalidades de gestão de pessoas.',
            ],
            [
                'name'          => 'Marketing',
                'key'           => 'marketing',
                'description'   => 'Papel para o departamento de marketing, com acesso a funcionalidades de marketing.',
            ],
            [
                'name'          => 'Gerente de Recursos',
                'key'           => 'resource_manager',
                'description'   => 'Papel para gerentes de recursos, com acesso a funcionalidades de gestão de recursos.',
            ],
            [
                'name'          => 'Vendas',
                'key'           => 'sales',
                'description'   => 'Papel para o departamento de vendas, com acesso a funcionalidades de vendas e CRM.',
            ],
            [
                'name'          => 'Gerente de Projeto',
                'key'           => 'project_manager',
                'description'   => 'Papel para gerentes de projeto, com acesso a funcionalidades de gestão de projetos.',
            ],
            [
                'name'          => 'Desenvolvedor',
                'key'           => 'developer',
                'description'   => 'Papel para desenvolvedores, com acesso a funcionalidades técnicas.',
            ],
            [
                'name'          => 'Convidado',
                'key'           => 'guest',
                'description'   => 'Papel com permissões limitadas para usuários convidados.',
            ]
        ];

        foreach ($globalRoles as $role) {
            Role::query()->firstOrCreate([
                'name'          => $role['name'],
                'key'           => $role['key'],
                'scope'         => \App\Enums\RoleScopeEnum::GLOBAL->value,
            ], [
                'description'   => $role['description'],
            ]);
        }

        foreach ($companyRoles as $role) {
            Role::query()->firstOrCreate([
                'name'          => $role['name'],
                'key'           => $role['key'],
                'scope'         => \App\Enums\RoleScopeEnum::COMPANY->value,
            ], [
                'description'   => $role['description'],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Este seed é idempotente; no rollback não removemos dados para evitar perda acidental.
    }
};
