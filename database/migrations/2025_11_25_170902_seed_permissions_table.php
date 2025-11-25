<?php

use App\Models\Permission;
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
        $rootPermissions = [
            // Allow Root Access
            [
                'name'          => 'Acesso Root',
                'key'           => 'root.access',
                'description'   => 'Permite acesso total ao sistema.',
            ],
        ];

        $userPermissions = [
            // Management Permissions
            [
                'name'          => 'Gerenciar Usuários',
                'key'           => 'user.manage',
                'description'   => 'Permite gerenciar usuários do sistema (Criar, Editar, Apagar).',
            ],
            [
                'name'          => 'Gerenciar Perfis',
                'key'           => 'resource_profile.manage',
                'description'   => 'Permite gerenciar perfis de recursos (Criar, Editar, Apagar).',
            ],
            [
                'name'          => 'Gerenciar Habilidades',
                'key'           => 'skill.manage',
                'description'   => 'Permite gerenciar habilidades (Criar, Editar, Apagar).',
            ],
            [
                'name'          => 'Gerenciar Empresas',
                'key'           => 'company.manage',
                'description'   => 'Permite gerenciar empresas (Criar, Editar, Apagar).',
            ],
            [
                'name'          => 'Gerenciar Horários de Trabalho',
                'key'           => 'work_schedule.manage',
                'description'   => 'Permite gerenciar horários de trabalho (Criar, Editar, Apagar).',
            ],
            [
                'name'          => 'Gerenciar Valor/Hora',
                'key'           => 'rate_history.manage',
                'description'   => 'Permite gerenciar histórico de valores/hora (Criar, Editar, Apagar).',
            ],
            [
                'name'          => 'Gerenciar Webhooks',
                'key'           => 'webhook.manage',
                'description'   => 'Permite gerenciar webhooks (Criar, Editar, Apagar).',
            ],
            [
                'name'          => 'Gerenciar Calendários',
                'key'           => 'calendar.manage',
                'description'   => 'Permite gerenciar calendários (Criar, Editar, Apagar).',
            ],
            [
                'name'          => 'Gerenciar Times',
                'key'           => 'team.manage',
                'description'   => 'Permite gerenciar times (Criar, Editar, Apagar).',
            ],
            [
                'name'          => 'Gerenciar Projetos',
                'key'           => 'project.manage',
                'description'   => 'Permite gerenciar projetos (Criar, Editar, Apagar).',
            ],
            [
                'name'          => 'Gerenciar Tarefas',
                'key'           => 'task.manage',
                'description'   => 'Permite gerenciar tarefas (Criar, Editar, Apagar).',
            ],
            [
                'name'          => 'Gerenciar Entradas de Tempo',
                'key'           => 'time_entry.manage',
                'description'   => 'Permite gerenciar entradas de tempo (Aprovar, Reprovar).',
            ],

            // Assignment Permissions

            [
                'name'          => 'Atribuir Usuários a Empresas',
                'key'           => 'company_user.assign',
                'description'   => 'Permite atribuir usuários a empresas específicas.',
            ],
            [
                'name'          => 'Atribuir Habilidades',
                'key'           => 'user_skill.assign',
                'description'   => 'Permite atribuir habilidades a usuários.',
            ],
            [
                'name'          => 'Atribuir Papéis',
                'key'           => 'company_user_role.assign',
                'description'   => 'Permite atribuir papéis a usuários.',
            ],
            [
                'name'          => 'Atribuir Usuários a Times',
                'key'           => 'team_members.assign',
                'description'   => 'Permite atribuir usuários a times.',
            ],
            [
                'name'          => 'Atribuir Usuários a Projetos ou Tarefas',
                'key'           => 'assignment.assign',
                'description'   => 'Permite atribuir usuários a projetos ou tarefas.',
            ],

            // Specific Action Permissions

            // --- Report Permissions ---

            [
                'name'          => 'Emitir Relatórios de Horas Trabalhadas',
                'key'           => 'report.user.generate',
                'description'   => 'Permite emitir relatórios detalhados de horas trabalhadas por usuários, projetos ou períodos.',
            ],
            [
                'name'          => 'Emitir Relatórios Financeiros',
                'key'           => 'report.financial.generate',
                'description'   => 'Permite emitir relatórios financeiros relacionados a faturamento e custos.',
            ],
            [
                'name'          => 'Emitir Relatórios de Produtividade',
                'key'           => 'report.productivity.generate',
                'description'   => 'Permite emitir relatórios de produtividade dos usuários e equipes.',
            ],
            [
                'name'          => 'Emitir Relatórios de Utilização de Recursos',
                'key'           => 'report.resource_utilization.generate',
                'description'   => 'Permite emitir relatórios sobre a utilização de recursos em projetos.',
            ],

            // --- PTO Permissions ---

            [
                'name'          => 'Revisar PTOs',
                'key'           => 'pto.review',
                'description'   => 'Permite revisar solicitações de PTO (Paid Time Off) (Aprovar e Reprovar).',
            ],
            [
                'name'          => 'Gerenciar Saldo de PTO',
                'key'           => 'pto.balance.manage',
                'description'   => 'Permite gerenciar o saldo de PTO dos usuários.',
            ]
        ];

        foreach ($rootPermissions as $permission) {
            Permission::query()->firstOrCreate([
                'name'          => $permission['name'],
                'key'           => $permission['key'],
                'scope'         => \App\Enums\PermissionScopeEnum::ROOT->value,
            ], [
                'description'   => $permission['description'],
            ]);
        }

        foreach ($userPermissions as $permission) {
            Permission::query()->firstOrCreate([
                'name'          => $permission['name'],
                'key'           => $permission['key'],
                'scope'         => \App\Enums\PermissionScopeEnum::USER->value,
            ], [
                'description'   => $permission['description'],
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
